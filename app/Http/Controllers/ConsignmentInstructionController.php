<?php

namespace App\Http\Controllers;

use App\Exports\ConsignmentInstructionExport;
use App\Jobs\StoreConsignmentMcMhJob;
use App\Jobs\StoreConsignmentMzJob;
use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\ShippingInstruction;
use App\Models\TransactionType;
use App\Models\YH003;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ConsignmentInstructionController extends Controller
{
    /**
     *
     */
    public function consignment_container()
    {
        $containers = Container::where('status', '=', 1)
            ->orderBy('arrival_date', 'ASC')
            ->orderBy('arrival_time', 'ASC')
            ->get();

        return view('consignment-instruction.container', ['containers' => $containers]);
    }

    /**
     *
     */
    public function consignment_create(Request $request)
    {
        $container = Container::findOrFail($request->container);
        $consignments = ConsignmentInstruction::where('container_id', '=', $request->container)->orderBy('created_at', 'DESC')->paginate(5);

        return view('consignment-instruction.create', ['container' => $container, 'consignments' => $consignments]);
    }

    /**
     *
     */
    public function consignment_store(Request $request)
    {
        $request->validate([
            'code_qr' => ['required', 'string', 'min:160'],
            'container_id' => ['required', 'numeric'],
        ]);

        $dataRequest = strtoupper($request->code_qr);

        list($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $part_qty, $supplier, $m, $serial, $o, $p, $q, $r, $s, $t, $u, $v, $w, $x, $y, $z, $part_no) = explode(',', $dataRequest);

        $data = ConsignmentInstruction::where(
            [
                ['serial', '=', $serial],
                ['supplier', '=', $supplier],
                ['part_no', 'LIKE', $part_no . '%']
            ]
        )->first();

        if ($data === null) {

            $container = Container::find($request->container_id);

            $shipping = ShippingInstruction::where(
                [
                    ['container', 'LIKE', $container->code],
                    ['arrival_date', $container->arrival_date],
                    ['arrival_time', $container->arrival_time],
                    ['part_no', 'LIKE', $part_no . '%'],
                    ['serial', 'LIKE', $supplier . $serial]
                ]
            )->first();

            if ($shipping !== null) {
                ConsignmentInstruction::storeConsignment($serial, $supplier, $part_qty, $part_no, 'L60', $request->container_id);
                $msg = 'Registro Exitoso';
                $status = 'success';
            } else {
                $msg = 'Este Item no Pertenece al Contenedor';
                $status = 'warning';
            }
        } else {
            $msg = 'Registro Duplicado';
            $status = 'warning';
        }

        return redirect()->back()->with($status, $msg);
    }

    /**
     *
     */
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
     *
     */
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

    /**
     *
     */
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

    /**
     *
     */
    public function consignment_finish(Request $request)
    {
        $id = $request->container_id;
        $container = $request->container_code;
        $date = $request->container_date;
        $time = $request->container_time;

        // $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        // $query = "CALL LX834OU.YPU180C";
        // $result = odbc_exec($conn, $query);

        Container::where('id', $id)->update(['status' => false]);

        ShippingInstruction::query()
            ->where([
                ['container', '=', $container],
                ['arrival_date', '=', $date],
                ['arrival_time', '=', $time],
                ['status', '=', true]
            ])
            ->update(['status' => false, 'search' => true]);

        return redirect('consignment-instruction-container');
    }

    // public function data_upload_index()
    // {
    //     return view('data-upload.index');
    // }

    // public function data_upload_store(Request $request)
    // {
    //     $file = $request->file('import_file');

    //     Excel::import(new DataUploadImport, $file);

    //     return redirect()->route('consigment-instruction.data-upload-index')->with('success', 'Datos Importados Correctamente');
    // }

    // public function data_upload_inventory()
    // {
    //     $consignmentRecords = ConsignmentInstruction::where('flag', false)->get();

    //     foreach ($consignmentRecords as $key => $consignmentRecord) {
    //         $item = Item::where('item_number', 'LIKE', '%' . $consignmentRecord->part_no . '%')->first();
    //         $location = Location::where('code', 'LIKE', '%' . $consignmentRecord->location . '%')->first();
    //         $transaccion = TransactionType::where('code', 'LIKE', 'U%')->first();

    //         if ($item !== null) {
    //             // $inventory = Inventory::where('item_id', $item->id)->first();

    //             // if ($inventory !== null) {
    //             //     $quantity = $inventory->opening_balance + $consignmentRecord->part_qty;

    //             //     $inventory->update(
    //             //         [
    //             //             'opening_balance' => $quantity,
    //             //             'item_id' => $item->id,
    //             //             'location_id' => $location->id
    //             //         ]
    //             //     );

    //             //     Input::storeInput($consignmentRecord->supplier, $consignmentRecord->serial, $item->id, $consignmentRecord->part_qty, '', $transaccion->id, $location->id);

    //             //     $consignmentRecord->update(['flag' => true]);
    //             // } else {
    //             //     Inventory::storeInventory($item->id, 0, $location->id, $consignmentRecord->part_qty);

    //             //     Input::storeInput($consignmentRecord->supplier, $consignmentRecord->serial, $item->id, $consignmentRecord->part_qty, '', $transaccion->id, $location->id);
    //             // }

    //             Input::storeInput($consignmentRecord->supplier, $consignmentRecord->serial, $item->id, $consignmentRecord->part_qty, '', $transaccion->id, $location->id);

    //             $consignmentRecord->update(['flag' => true]);
    //         }
    //     }

    //     return redirect()->route('consigment-instruction.data-upload-index')->with('success', 'Datos Guardados Correctamente');
    // }

    /**
     *
     */
    public function barcode(Request $request)
    {
        $container = Container::find($request->container_id);

        return view('consignment-instruction.barcode', ['container' => $container]);
    }

    /**
     *
     */
    public function storeBarcode(Request $request)
    {
        $request->validate([
            'part' => ['required', 'string'],
            'quantity' => ['required', 'string'],
            'supplier' => ['required', 'string'],
            'serial' => ['required', 'string'],
        ]);

        $container = Container::find($request->container);

        $data = ConsignmentInstruction::where(
            [
                ['serial', '=', strtoupper(substr($request->serial, 1))],
                ['supplier', '=', strtoupper(substr($request->supplier, 1))]
            ]
        )->first();

        if ($data === null) {
            ConsignmentInstruction::storeConsignment(
                strtoupper(substr($request->serial, 1)),
                strtoupper(substr($request->supplier, 1)),
                strtoupper(substr($request->quantity, 1)),
                strtoupper(substr($request->part, 1)),
                'L60',
                $request->container
            );

            return view('consignment-instruction.barcode', ['container' => $container])->with('success', 'Registro Exitoso');
        } else {
            return view('consignment-instruction.barcode', ['container' => $container])->with('warning', 'Registro Duplicado');
        }
    }

    /**
     *
     */
    public function consigmentBarcodeIndex()
    {
        return view('consignment-instruction.consignment-barcode');
    }

    public function consignmentBarcodeStore(Request $request)
    {
        $request->validate([
            'scan' => ['required', 'string', 'max:161', 'min:35']
        ]);

        $code = strtoupper($request->scan);

        if (strlen($code) == 35) {
            $serial = substr($code, 0, 10);
            $part_no = substr($code, 10, 10);
            $snp = substr($code, 20, 6);
            $supplier = substr($code, 26, 5);
            $type = substr($code, 31, 2);

            $input = Input::where(
                [
                    ['supplier', 'LIKE', $supplier],
                    ['serial', 'LIKE', $serial],
                    ['item_quantity', $snp],
                    ['type_consignment', 'LIKE', $type],
                ]
            )->first();

            if ($input === null) {

                // StoreConsignmentMcMhJob::dispatch($serial, $part_no, $snp, $supplier, $type);

                $item = Item::where('item_number', 'LIKE', $part_no . '%')->first();
                $transaction = TransactionType::where('code', 'LIKE', 'U3')->first();
                $location = Location::where('code', 'LIKE', 'L60%')->first();

                $input = Input::create([
                    'supplier' => $supplier,
                    'serial' => $serial,
                    'item_id' => $item->id,
                    'item_quantity' => $snp,
                    'type_consignment' => $type,
                    'transaction_type_id' => $transaction->id,
                    'location_id' => $location->id,
                    'user_id' => Auth::id()
                ]);

                $yh003 = YH003::query()->insert([
                    'H3PROD' => $item->item_number,
                    'H3SUCD' => $supplier,
                    'H3SENO' => $serial,
                    'H3RQTY' => $snp,
                    'H3CUSR' => Auth::user()->user_infor ?? '',
                    'H3RDTE' => Carbon::parse($input->created_at)->format('Ymd'),
                    'H3RTIM' => Carbon::parse($input->created_at)->format('His')
                ]);

                $inventory = Inventory::where([
                    ['item_id', '=', $item->id],
                    ['location_id', '=', $location->id]
                ])->first();


                if ($inventory !== null) {
                    $qty = $inventory->quantity ?? 0;

                    $sum = 0;
                    $sum = $snp + $qty;

                    $inventory->update(['quantity' => $sum]);
                } else {
                    $inventory = Inventory::create([
                        'item_id' => $item->id,
                        'location_id' => $location->id,
                        'quantity' => $snp
                    ]);
                }

                $respone = 'success';
                $mesage = 'El Registro del Material se Hizo Correctamente.';
            } else {
                $respone = 'warning';
                $mesage = 'Material Registrado Anteriormente';
            }
        }
        //
        // else if (strlen($code) == 161) {
        //     list($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $snp, $supplier, $m, $serial, $o, $p, $q, $r, $s, $t, $u, $v, $w, $x, $y, $z, $part_no) = explode(',', $code);

        //     $input = Input::where(
        //         [
        //             ['supplier', 'LIKE', $supplier],
        //             ['serial', 'LIKE', $serial],
        //             ['item_quantity', $snp],
        //             ['type_consignment', 'LIKE', 'MZ'],
        //         ]
        //     )->first();

        //     if ($input === null) {
        //         $type = 'MZ';
        //         StoreConsignmentMzJob::dispatch($serial, $part_no, $snp, $supplier, $type);
        //         $respone = 'success';
        //         $mesage = 'El Registro del Material se Hizo Correctamente.';
        //     } else {
        //         $respone = 'warning';
        //         $mesage = 'Material Registrado Anteriormente';
        //     }
        // }
        //
        else {
            $respone = 'warning';
            $mesage = 'El Codigo de Material no es Valido.';
        }

        return redirect()->back()->with($respone, $mesage);
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
