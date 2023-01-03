<?php

namespace App\Http\Controllers;

use App\Exports\ConsignmentInstructionExport;
use App\Imports\DataUploadImport;
use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\ShippingInstruction;
use App\Models\TransactionType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ConsignmentInstructionController extends Controller
{

    public function consignment_container()
    {
        $containers = Container::where([
            ['status', '=', 1],
            ['arrival_date', '=', Carbon::now()]
        ])
            ->orderBy('arrival_date', 'ASC')
            ->orderBy('arrival_time', 'ASC')
            ->get();

        return view('consignment-instruction.container', ['containers' => $containers]);
    }

    public function consignment_create(Request $request)
    {
        $container = Container::findOrFail($request->container);
        $consignments = ConsignmentInstruction::where('container_id', '=', $request->container)->orderBy('created_at', 'DESC')->paginate(5);

        return view('consignment-instruction.create', ['container' => $container, 'consignments' => $consignments]);
    }

    public function consignment_store(Request $request)
    {
        $request->validate([
            'code_qr' => ['required', 'string', 'min:160'],
            'container_id' => ['required', 'numeric'],
        ]);

        $dataRequest = strtoupper($request->code_qr);

        list($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $part_qty, $supplier, $m, $serial, $o, $p, $q, $r, $s, $t, $u, $v, $w, $x, $y, $z, $part_no) = explode(',', $dataRequest);

        $data = ConsignmentInstruction::where([['serial', '=', $serial], ['supplier', '=', $supplier]])->first();

        if ($data === null) {
            ConsignmentInstruction::storeConsignment($serial, $supplier, $part_qty, $part_no, 'L60', $request->container_id);

            return redirect()->back()->with('success', 'Registro Exitoso');
        } else {
            return redirect()->back()->with('warning', 'Registro Duplicado');
        }
    }

    public function consignment_check(Request $request)
    {
        $id = $request->container_id;
        $container = $request->container_code;
        $date = $request->container_date;
        $time = $request->container_time;

        $consignments = ConsignmentInstruction::query()
            ->join('containers', 'consignment_instructions.container_id', '=', 'containers.id')
            ->where([
                ['containers.code', '=', $container],
                ['containers.arrival_date', '=', $date],
                ['containers.arrival_time', '=', $time],
                ['containers.status', '=', true]
            ])
            ->orderBy('supplier', 'ASC')
            ->orderBy('serial', 'ASC')
            ->get();

        $shipments = ShippingInstruction::query()
            ->where([
                ['container', '=', $container],
                ['arrival_date', '=', $date],
                ['arrival_time', '=', $time],
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
                // echo $key . " " . $shipping->serial . " No Existe </br>";
                array_push($arrayNotFound, [
                    'container' => $shipping->container,
                    'invoice' => $shipping->invoice,
                    'serial' => $shipping->serial,
                    'part_no' => $shipping->part_no,
                    'part_qty' => $shipping->part_qty,
                    'arrival_date' => $shipping->arrival_date,
                    'arrival_time' => $shipping->arrival_time,
                ]);
            }
        }

        return view('consignment-instruction.check', [
            'dataArray' => $arrayNotFound,
            'found' => count($arrayNotFound),
            'container_id' => $id,
            'container_code' => $container,
            'container_date' => $date,
            'container_time' => $time
        ]);
    }

    public function search(array $arr, $start, $end, $x)
    {
        // if (count($arr) === 0) return false;

        // $low = 0;
        // $high = count($arr) - 1;

        // while ($low <= $high) {
        //     $mid = floor(($low + $high) / 2);

        //     if ($arr[$mid] == $x) {
        //         return true;
        //     }

        //     if ($x < $arr[$mid]) {
        //         $high = $mid - 1;
        //     } else {
        //         $low = $mid + 1;
        //     }
        // }

        // return false;

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

    public function reportFount(Request $request)
    {
        $id = $request->container_id;
        $container = $request->container_code;
        $date = $request->container_date;
        $time = $request->container_time;

        $consignments = ConsignmentInstruction::query()
            ->join('containers', 'consignment_instructions.container_id', '=', 'containers.id')
            ->where([
                ['containers.code', '=', $container],
                ['containers.arrival_date', '=', $date],
                ['containers.arrival_time', '=', $time],
                ['containers.status', '=', true]
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

    public function reportNotFount(Request $request)
    {
        $id = $request->container_id;
        $container = $request->container_code;
        $date = $request->container_date;
        $time = $request->container_time;

        $consignments = ConsignmentInstruction::query()
            ->join('containers', 'consignment_instructions.container_id', '=', 'containers.id')
            ->where([
                ['containers.code', '=', $container],
                ['containers.arrival_date', '=', $date],
                ['containers.arrival_time', '=', $time],
                ['containers.status', '=', true]
            ])
            ->orderBy('supplier', 'ASC')
            ->orderBy('serial', 'ASC')
            ->get();

        $shipments = ShippingInstruction::query()
            ->where([
                ['container', '=', $container],
                ['arrival_date', '=', $date],
                ['arrival_time', '=', $time],
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
                    'invoice' => $shipping->invoice,
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

    public function consignment_finish(Request $request)
    {
        $id = $request->container_id;
        $container = $request->container_code;
        $date = $request->container_date;
        $time = $request->container_time;

        $consignments = ConsignmentInstruction::query()
            ->join('containers', 'consignment_instructions.container_id', '=', 'containers.id')
            ->where([
                ['containers.code', '=', $container],
                ['containers.arrival_date', '=', $date],
                ['containers.arrival_time', '=', $time],
                ['containers.status', '=', true]
            ])
            ->orderBy('supplier', 'ASC')
            ->orderBy('serial', 'ASC')
            ->get();

        foreach ($consignments as $key => $consignment) {
            $item = Item::where('item_number', 'LIKE', '%' . $consignment->part_no . '%')->firstOrFail();
            $transaccion = TransactionType::where('code', '=', 'U3')->firstOrFail();
            $location = Location::where('code', 'LIKE', 'L60%')->firstOrFail();
            Input::storeInputConsignment(
                $consignment->supplier,
                $consignment->serial,
                $item->id,
                $consignment->part_qty,
                $consignment->container_id,
                $transaccion->id,
                $location->id,
            );
        }

        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU02.YPU180C";
        $result = odbc_exec($conn, $query);

        Container::where('id', $id)->update(['status' => false]);
        ShippingInstruction::query()
            ->where([
                ['container', '=', $container],
                ['arrival_date', '=', $date],
                ['arrival_time', '=', $time],
                ['status', '=', true]
            ])
            ->update(['status' => false]);

        return redirect('consignment-instruction-container');
    }

    public function data_upload_index()
    {
        return view('data-upload.index');
    }

    public function data_upload_store(Request $request)
    {
        $file = $request->file('import_file');

        Excel::import(new DataUploadImport, $file);

        return redirect()->route('consigment-instruction.data-upload-index')->with('success', 'Datos Importados Correctamente');
    }

    public function data_upload_inventory()
    {
        $consignmentRecords = ConsignmentInstruction::where('flag', false)->get();

        foreach ($consignmentRecords as $key => $consignmentRecord) {
            $item = Item::where('item_number', 'LIKE', '%' . $consignmentRecord->part_no . '%')->first();
            $location = Location::where('code', 'LIKE', '%' . $consignmentRecord->location . '%')->first();
            $transaccion = TransactionType::where('code', 'LIKE', 'U%')->first();

            if ($item !== null) {
                // $inventory = Inventory::where('item_id', $item->id)->first();

                // if ($inventory !== null) {
                //     $quantity = $inventory->opening_balance + $consignmentRecord->part_qty;

                //     $inventory->update(
                //         [
                //             'opening_balance' => $quantity,
                //             'item_id' => $item->id,
                //             'location_id' => $location->id
                //         ]
                //     );

                //     Input::storeInput($consignmentRecord->supplier, $consignmentRecord->serial, $item->id, $consignmentRecord->part_qty, '', $transaccion->id, $location->id);

                //     $consignmentRecord->update(['flag' => true]);
                // } else {
                //     Inventory::storeInventory($item->id, 0, $location->id, $consignmentRecord->part_qty);

                //     Input::storeInput($consignmentRecord->supplier, $consignmentRecord->serial, $item->id, $consignmentRecord->part_qty, '', $transaccion->id, $location->id);
                // }

                Input::storeInput($consignmentRecord->supplier, $consignmentRecord->serial, $item->id, $consignmentRecord->part_qty, '', $transaccion->id, $location->id);

            }
        }

        return redirect()->route('consigment-instruction.data-upload-index')->with('success', 'Datos Guardados Correctamente');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
        //
    }
}
