<?php

namespace App\Http\Controllers;

use App\Exports\ConsignmentInstructionExport;
use App\Imports\ShippingInstructionImport;
use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\Input;
use App\Models\Item;
use App\Models\Location;
use App\Models\ShippingInstruction;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ShippingInstructionController extends Controller
{
    /**
     *
     */
    public function reportShipping(Request $request)
    {
        $search = $request->search ?? '';

        $containers = Container::where('code', 'LIKE', '%' . $search . '%')->orderByRaw('arrival_date DESC, arrival_time DESC')->paginate(10);

        return view('shipping-instruction.report', ['containers' => $containers]);
    }

    /**
     *
     */
    public function downloadShipping(Request $request)
    {
        $container = Container::find($request->id);

        $consignments = ConsignmentInstruction::query()
            ->join('containers', 'consignment_instructions.container_id', '=', 'containers.id')
            ->where([
                ['containers.code', '=', $container->code],
                ['containers.arrival_date', '=', $container->arrival_date],
                ['containers.arrival_time', '=', $container->arrival_time],
            ])
            ->orderBy('supplier', 'ASC')
            ->orderBy('serial', 'ASC')
            ->get();

        $array_consignment = [];
        foreach ($consignments as $key => $consignment) {
            $serial = $consignment->supplier . $consignment->serial;
            array_push($array_consignment, [
                'container' => $consignment->container->code,
                // 'invoice' => $consignment->invoice,
                'serial' => $serial,
                'part_no' => $consignment->part_no,
                'part_qty' => $consignment->part_qty,
                'arrival_date' => $consignment->arrival_date,
                'arrival_time' => $consignment->arrival_time,
            ]);
        }

        return Excel::download(new ConsignmentInstructionExport($array_consignment), 'Scanned.xlsx');
    }

    /**
     *
     */
    public function noFound(Request $request)
    {
        $container = Container::find($request->id);

        $consignments = ConsignmentInstruction::where('container_id', $container->id)
            ->orderBy('supplier', 'ASC')
            ->orderBy('serial', 'ASC')
            ->get();

        $shipments = ShippingInstruction::query()
            ->where([
                ['container', 'LIKE', $container->code],
                ['arrival_date', '=', $container->arrival_date],
                ['arrival_time', '=', $container->arrival_time],
            ])
            ->orderBy('serial', 'ASC')
            ->get();

        $array_consignment = [];
        foreach ($consignments as $key => $consignment) {
            $serial = $consignment->supplier . $consignment->serial;
            array_push($array_consignment, $serial);
        }

        $arrayNotFound = [];
        foreach ($shipments as $key => $shipping) {
            if (self::search($array_consignment, 0, count($array_consignment) - 1, $shipping->serial) === false) {
                array_push($arrayNotFound, [
                    'container' => $shipping->container,
                    // 'invoice' => $shipping->invoice,
                    'serial' => $shipping->serial,
                    'part_no' => $shipping->part_no,
                    'part_qty' => $shipping->part_qty,
                    'arrival_date' => $shipping->arrival_date,
                    'arrival_time' => $shipping->arrival_time,
                ]);
            }
        }

        return Excel::download(new ConsignmentInstructionExport($arrayNotFound), 'NotFound.xlsx');
    }

    /**
     *
     */
    public function search(array $arr, $start, $end, $x)
    {
        if ($end < $start)
            return false;

        $mid = floor(($end + $start) / 2);
        if ($arr[$mid] == $x)
            return true;

        elseif ($arr[$mid] > $x) {

            return self::search($arr, $start, $mid - 1, $x);
        } else {

            return self::search($arr, $mid + 1, $end, $x);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippings = ShippingInstruction::where('status', '=', 1)->orderBy('id', 'DESC')->paginate(10);

        return view('shipping-instruction.index', ['shippings' => $shippings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'import_file' => 'required'
        // ]);

        $file = $request->file('import_file');

        Excel::import(new ShippingInstructionImport, $file);

        $containers = ShippingInstruction::query()
            ->select('container', 'arrival_date', 'arrival_time')
            ->where('status', true)
            ->distinct()
            ->get();

        foreach ($containers as $key => $container) {
            if ($container->arrival_date != null && $container->arrival_time != null) {
                Container::storeContainer($container->container, $container->arrival_date, $container->arrival_time);
                $respone = "success";
                $msg = "Documento Importado Exitosamente";
            } else {
                $respone = "warning";
                $msg = "Error al Cargar Documento";
            }
        }

        return redirect()->back()->with($respone, $msg);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $container = Container::find($id);

        if ($container->status === true) {
            $consignment = ConsignmentInstruction::where('container_id', $container->id)->count();
            if ($consignment <= 0) {
                $shipping = ShippingInstruction::where('container', $container->code)->delete();
                $container->delete();
            }
        }

        return redirect()->back();
    }

    /**
     *
     */
    public function scan()
    {
        return view('shipping-instruction.scan');
    }

    /**
     *
     */
    public function storeScan(Request $request)
    {
        $request->validate([
            'qr' => ['required', 'string', 'max:161', 'min:161']
        ]);

        $data = strtoupper($request->qr);

        list($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $part_qty, $supplier, $m, $serial, $o, $p, $q, $r, $s, $t, $u, $v, $w, $x, $y, $z, $part_no) = explode(',', $data);

        $dataConsigement = ConsignmentInstruction::where([
            ['serial', '=', $serial],
            ['supplier', '=', $supplier]
        ])->first();

        $shipping = ShippingInstruction::where([
            ['serial', '=', $supplier . $serial],
            ['search', '=', false]
        ])->first();

        if ($dataConsigement === null) {
            if ($shipping !== null) {
                $container = Container::where('code', 'LIKE', '%' . $shipping->container . '%')->first();
                ConsignmentInstruction::storeConsignment($serial, $supplier, $part_qty, $part_no, 'L60', $container->id);
                $shipping->update(['search' => true]);
                $respone = 'success';
                $mesage = 'El Registro del Material se Hizo Correctamente.';
            } else {
                $respone = 'warning';
                $mesage = 'Serial no Encontrado';
            }
        } else {
            $respone = 'warning';
            $mesage = 'Material Registrado Anteriormente';
        }
        return redirect()->back()->with($respone, $mesage);
    }

    /**
     *
     */
    public function scanBarCode()
    {
        return view('shipping-instruction.scanBarCode');
    }

    /**
     *
     */
    public function storeBarCode(Request $request)
    {
        $request->validate([
            'part' => ['required', 'string', 'min:10', 'max:10'],
            'quantity' => ['required', 'string', 'min:7', 'max:7'],
            'supplier' => ['required', 'string', 'min:6', 'max:6'],
            'serial' => ['required', 'string', 'min:10', 'max:10'],
        ]);

        $supplier =  strtoupper(substr($request->supplier, 1));
        $serial = strtoupper(substr($request->serial, 1));
        $part = strtoupper(substr($request->part, 1));
        $quantity = strtoupper(substr($request->quantity, 1));

        $dataConsigement = ConsignmentInstruction::where([
            ['serial', '=', $serial],
            ['supplier', '=', $supplier]
        ])->first();

        $dataShipping = ShippingInstruction::where([
            ['serial', '=', $supplier . $serial],
            ['search', '=', false]
        ])->first();

        if ($dataConsigement === null) {
            if ($dataShipping !== null) {
                $container = Container::where('code', 'LIKE', '%' . $dataShipping->container . '%')->first();
                ConsignmentInstruction::storeConsignment($serial, $supplier, $quantity, $part, 'L60', $container->id);
                $dataShipping->update(['search' => true]);
                $respone = 'success';
                $mesage = 'Registro Exitoso';
            } else {
                $respone = 'warning';
                $mesage = 'No se encontro en Shipping o Posiblemnte se Registro Anteriormente';
            }
        } else {
            $respone = 'warning';
            $mesage = 'Se Encuentra Registrado';
        }

        return redirect()->back()->with($respone, $mesage);
    }

    /**
     *
     */
    public function reportConsignments()
    {
        return view('shipping-instruction.reportConsigments');
    }

    /**
     *
     */
    public function downloadConsignment(Request $request)
    {
        $request->validate([
            'type' => ['required'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date']
        ]);

        dd($request->all());
    }
}
