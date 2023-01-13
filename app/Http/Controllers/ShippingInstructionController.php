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
use Maatwebsite\Excel\Facades\Excel;

class ShippingInstructionController extends Controller
{
    /**
     *
     */
    public function reportShipping()
    {
        $containers = Container::orderByRaw('arrival_date DESC, arrival_time DESC')->paginate(10);

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

    public function noFound(Request $request)
    {
        $container = Container::find($request->id);

        $consignments = ConsignmentInstruction::where('container_id', $container->id)
            ->orderBy('supplier', 'ASC')
            ->orderBy('serial', 'ASC')
            ->get();

        $shipments = ShippingInstruction::query()
            ->where([
                ['container', '=', $container->code],
                ['arrival_date', '=', $container->arrival_date],
                ['arrival_time', '=', $container->arrival_time],
                ['status', '=', true]
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
            Container::storeContainer($container->container, $container->arrival_date, $container->arrival_time);
        }

        return redirect()->back()->with('success', 'Documento Importado Exitosamente');
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
        $containers = Container::orderByRaw('arrival_date DESC, arrival_time DESC')->paginate(10);

        return view('shipping-instruction.report', ['containers' => $containers]);
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
        $data = strtoupper($request->qr);

        list($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $part_qty, $supplier, $m, $serial, $o, $p, $q, $r, $s, $t, $u, $v, $w, $x, $y, $z, $part_no) = explode(',', $data);

        $shipping = ShippingInstruction::where([
            ['serial', '=', $supplier . $serial],
            ['search', '=', false]
        ])->first();

        if ($shipping !== null) {
            $item = Item::where('item_number', 'LIKE', $part_no . '%')->first();
            $container = Container::where('code', 'LIKE', '%' . $shipping->container . '%')->first();
            $transaccion = TransactionType::where('code', '=', 'U3')->first();
            $location = Location::where('code', 'LIKE', 'L60%')->first();

            ConsignmentInstruction::storeConsignment($serial, $supplier, $part_qty, $part_no, 'L60', $container->id);

            Input::storeInput($supplier, $serial, $item->id, $item->item_number, $part_qty, $container->id, $transaccion->id, $location->id);

            $shipping->update(['search' => true]);
        } else {
            return redirect()->back()->with('warning', 'Serial No Encontrado O Anteriormente Registrado');
        }

        return redirect()->back()->with('success', 'Registro Exitoso');
    }
}
