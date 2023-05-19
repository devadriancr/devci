<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\DeliveryProduction;
use App\Models\ShippingInstruction;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Output;
use App\Models\item;
use App\Models\input;
use App\Models\YI007;
use App\Models\YH003;
use Illuminate\Support\Facades\DB;
use App\Models\transactiontype;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

use App\Exports\DeliveryExport as ExportsDeliveryExport;

class DeiveryProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $travels = DeliveryProduction::where('deliverysupplier', null)->orderby('id', 'desc')->paginate(10);
        return view('delivery_line.index', ['travels' => $travels]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $location = location::where('code', 'like', '%L12%')->first();
        $use = Auth::user()->id;
        $No = DeliveryProduction::create([
            'control_number' => $request->number_control,
            'location_id' => $location->id,
            'finish' => 0,
            'user_id' => $use
        ]);
        $scan =  input::where('delivery_production_id', $No->id)->simplePaginate(10);
        return view('delivery_line.scan', ['entrega' => $No, 'scan' => $scan]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DeliveryProduction::where('id', $request->Delivery_id)
            ->update(['finish' => 1]);
        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU.YIN151C";
        $result = odbc_exec($conn, $query);

        return redirect()->action([DeiveryProductionController::class, 'index']);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\deliveryproductions  $deliveryproductions
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\deliveryproductions  $deliveryproductions
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
    }
    public function inventario_nuevo($serial, $item, $loc, $cantidad)
    {
        $serial_18 = $serial . '         ';
        $operacion = Inventory::where('location_id', $loc)->where('item_id', $item)->first();
        if (is_null($operacion)) {
            $inv = 0;
        } else {
            $inv = $operacion->quantity;
        }

        $total = $inv + $cantidad;
        $mov = Inventory::updateOrCreate(
            ['location_id' => $loc, 'item_id' => $item],
            ['quantity' => $total]

        );
    }

    // public function inventario($serial, $item, $number_item, $loc, $cantidad, $loc_ant, $fechahora, $WH, $wh_act)
    // {
    //     $serial_18 = $serial . '         ';
    //     $operacion = Inventory::where('location_id', $loc)->where('item_id', $item)->first();
    //     $operacion_ant = Inventory::where('location_id', $loc_ant)->where('item_id', $item)->first();
    //     if (is_null($operacion)) {
    //         $inv = 0;
    //     } else {
    //         $inv = $operacion->quantity;
    //     }
    //     if (is_null($operacion_ant)) {
    //         $inv_ant = 0;
    //     } else {
    //         $inv_ant = $operacion_ant->quantity;
    //     }

    //     $total = $inv + $cantidad;
    //     $totalant = $inv_ant - $cantidad;
    //     $mov = Inventory::updateOrCreate(
    //         ['location_id' => $loc, 'item_id' => $item],
    //         ['quantity' => $total]

    //     );
    //     $mov = Inventory::updateOrCreate(
    //         ['location_id' => $loc_ant, 'item_id' => $item],
    //         ['quantity' => $totalant]

    //     );
    //     $fechascan = date('Ymd', strtotime($fechahora));
    //     $horascan = date('His', strtotime($fechahora));
    //     $fechainfor = date('Ymd', strtotime('now'));
    //     $hora = date('His', time());
    //     $use = Auth::user()->user_infor ?? 'ykms';
    //     $infor = YI007::Query()->insert(
    //         [
    //             'I7PROD' => $number_item,
    //             'I7SENO' => $serial ?? '',
    //             'I7TFLG' => 'O',
    //             'I7TDTE' => $fechascan,
    //             'I7TTIM' => $horascan,
    //             'I7TQTY' => $cantidad,
    //             'I7WHS' =>  $WH,
    //             'I7CUSR' => 'YKMS',
    //             'I7CCDT' => $fechainfor,
    //             'I7CCTM' => $hora,
    //         ]

    //     );
    //     $infor = YI007::Query()->insert(
    //         [
    //             'I7PROD' => $number_item,
    //             'I7SENO' => $serial,
    //             'I7TFLG' => 'I',
    //             'I7TDTE' => $fechascan,
    //             'I7TTIM' => $horascan,
    //             'I7TQTY' => $cantidad,
    //             'I7WHS' => $wh_act,
    //             'I7CUSR' => 'YKMS',
    //             'I7CCDT' => $fechainfor,
    //             'I7CCTM' => $hora,
    //         ]
    //     );
    // }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\deliveryproductions  $deliveryproductions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $error = 0;
        $location = location::with('warehouse')->find($request->location_id);
        if (strlen($request->serial) == 35) {
            $serial = substr($request->serial, -35, 10);
            $number_part = substr($request->serial, -25, 10);
            $quantity = substr($request->serial, -15, 6);
            $supplier = substr($request->serial, -9, 5);
            $type_consigment = substr($request->serial, -4, 2);
        } else {
            $cadena = explode(",", $request->serial);
            if (count($cadena) != 27) {

                $error = 1;
                $message = 'ESCANEO INCORRECTO';
            } else {
                $serial = $cadena[13];
                $number_part = end($cadena);
                $quantity = $cadena[10];
                $supplier = $cadena[11];
                $type_consigment = 'MY/MZ';
            }
        }
        if ($error == 0) {
            $item = DB::table('items')->whereRaw("item_number like  '" .  $number_part . "%'")->first();
            if ($item == false) {
                $error = 2;
                $message = 'Item no existe';
            }
        }
        if ($error == 0) {
            $loc_act_id = location::with('warehouse')->where('code', 'like', 'L61%')->first();
            $serial_exist = input::where([['serial', $serial], ['supplier', $supplier], ['item_id', $item->id]])->orderby('id', 'desc')->first();

            if (strlen($serial) == 9) {

                $type_consigment = $serial_exist->type_consignment ?? 'MY/MZ';
            }
            if ($serial_exist == null) {
                $error = 5;
                $message = 'Serial no encontrado con ese proveedor';
            } else {
                if (strlen($serial_exist->type_consignment) == 0) {
                    $type_consigment = 'MY/MZ';
                }
            }
            if ($error == 0) {
                if ($serial_exist->location_id ==  $loc_act_id->id) {
                    $error = 12;
                    $message = 'Serial no se encuentra en almacen de YKM';
                }
            }
            if ($error == 0) {
                if ($serial_exist->delivery_production_id ==  $request->delivery_id) {
                    $error = 3;
                    $message = 'serial ya fue escaneado';
                }
            }
            if ($error == 0) {
                if ($serial_exist->delivery_production_id != null) {
                    $error = 10;
                    $message = 'Serial ya se entrego anteriormente.';
                }
            }
        }
        $location_old = location::with('warehouse')->where('code', 'like', 'L60%')->first();
        $location_new = location::with('warehouse')->where('code', 'like', 'L12%')->first();
        $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();
        if ($error == 0) {

            Output::create([
                'supplier' =>  $supplier,
                'serial' => $serial,
                'item_id' => $item->id,
                'item_quantity' =>  $quantity,
                'transaction_type_id' => $Transaction_type->id,
                'delivery_production_id' => $request->delivery_id,
                'location_id' => $location_old->id,
                'user_id' =>     Auth::user()->id
            ]);

            $re = input::create([
                'supplier' =>  $supplier,
                'serial' => $serial,
                'item_id' => $item->id,
                'item_quantity' =>  $quantity,
                'type_consignment' =>  $type_consigment,
                'transaction_type_id' => $Transaction_type->id,
                'delivery_production_id' => $request->delivery_id,
                'location_id' => $request->location_id,
                'user_id' =>    Auth::user()->id
            ]);

            $operacion = Inventory::where('location_id', $location->id)->where('item_id', $item->id)->first();
            $operacion_ant = Inventory::where('location_id', $location_old->id)->where('item_id', $item->id)->first();
            if (is_null($operacion)) {
                $inv = 0;
            } else {
                $inv = $operacion->quantity;
            }
            if (is_null($operacion_ant)) {
                $inv_ant = 0;
            } else {
                $inv_ant = $operacion_ant->quantity;
            }

            $total = $inv + $quantity;
            $totalant = $inv_ant - $quantity;
            Inventory::updateOrCreate(
                ['location_id' => $location->id, 'item_id' => $item->id],
                ['quantity' => $total]

            );

            Inventory::updateOrCreate(
                ['location_id' => $location_old->id, 'item_id' => $item->id],
                ['quantity' => $totalant]

            );
            $fechascan = date('Ymd', strtotime($re->created_at));
            $horascan = date('His', strtotime($re->created_at));
            $fechainfor = date('Ymd', strtotime('now'));
            $hora = date('His', time());
            YI007::Query()->insert(
                [
                    'I7PROD' =>  $item->item_number,
                    'I7SENO' => $serial ?? '',
                    'I7TFLG' => 'O',
                    'I7TDTE' => $fechascan,
                    'I7TTIM' => $horascan,
                    'I7TQTY' => $quantity,
                    'I7WHS' =>  $location_old->warehouse->code,
                    'I7CUSR' => 'YKMS',
                    'I7CCDT' => $fechainfor,
                    'I7CCTM' => $hora,
                ]

            );
            YI007::Query()->insert(
                [
                    'I7PROD' => $item->item_number,
                    'I7SENO' => $serial,
                    'I7TFLG' => 'I',
                    'I7TDTE' => $fechascan,
                    'I7TTIM' => $horascan,
                    'I7TQTY' => $quantity,
                    'I7WHS' => $location->warehouse->code,
                    'I7CUSR' => 'YKMS',
                    'I7CCDT' => $fechainfor,
                    'I7CCTM' => $hora,
                ]
            );
            $message = 'Serial capturado exitosamente';
        } else {
            if ($error == 5) {
                $re = input::create([
                    'supplier' =>  $supplier,
                    'serial' => $serial,
                    'item_id' => $item->id,
                    'type_consignment' =>  $type_consigment,
                    'item_quantity' =>  $quantity,
                    'transaction_type_id' => $Transaction_type->id,
                    'location_id' => $location_old->id,
                    'user_id' =>     $use = Auth::user()->id
                ]);
                $fechascan = date('Ymd', strtotime($re->created_at));
                $horascan = date('His', strtotime($re->created_at));
                $fechainfor = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                //-----------------------------------------------------------------------------------
                $operacion = Inventory::where('location_id', $location_old->id)->where('item_id', $item->id)->first();
                if (is_null($operacion)) {
                    $inv = 0;
                } else {
                    $inv = $operacion->quantity;
                }
                $total = $inv + $quantity;
                $mov = Inventory::updateOrCreate(
                    ['location_id' => $location_old->id, 'item_id' => $item->id],
                    ['quantity' => $total]
                );

                //-----------------------------------------------------------
                YI007::Query()->insert(
                    [
                        'I7PROD' => $item->item_number,
                        'I7SENO' => $serial,
                        'I7TFLG' => 'A',
                        'I7TDTE' => $fechascan,
                        'I7TTIM' => $horascan,
                        'I7TQTY' =>  $quantity,
                        'I7WHS' =>   $location_old->warehouse->code,
                        'I7CUSR' => 'YKMS',
                        'I7CCDT' => $fechainfor,
                        'I7CCTM' => $hora,
                    ]
                );
                $serial_18 = $serial . '         ';
                $operacion = Inventory::where('location_id', $location->id)->where('item_id', $item->id)->first();
                if (is_null($operacion)) {
                    $inv = 0;
                } else {
                    $inv = $operacion->quantity;
                }

                $total = $inv + $quantity;
                $mov = Inventory::updateOrCreate(
                    ['location_id' => $location->id, 'item_id' => $item->id],
                    ['quantity' => $total]

                );
                Output::create([
                    'supplier' =>  $supplier,
                    'serial' => $serial,
                    'item_id' => $item->id,
                    'item_quantity' =>  $quantity,
                    'transaction_type_id' => $Transaction_type->id,
                    'delivery_production_id' => $request->delivery_id,
                    'location_id' => $location_old->id,
                    'user_id' =>      Auth::user()->id

                ]);
                $re = input::create([
                    'supplier' =>  $supplier,
                    'serial' => $serial,
                    'item_id' => $item->id,
                    'item_quantity' => $quantity,
                    'type_consignment' =>  $type_consigment,
                    'transaction_type_id' => $Transaction_type->id,
                    'delivery_production_id' => $request->delivery_id,
                    'location_id' => $request->location_id,
                    'user_id' =>   Auth::user()->id
                ]);

                $message = ' Serial dado de alta exitosamente ';
                $operacion = Inventory::where('location_id', $location->id)->where('item_id', $item->id)->first();
                $operacion_ant = Inventory::where('location_id', $location_old->id)->where('item_id', $item->id)->first();
                if (is_null($operacion)) {
                    $inv = 0;
                } else {
                    $inv = $operacion->quantity;
                }
                if (is_null($operacion_ant)) {
                    $inv_ant = 0;
                } else {
                    $inv_ant = $operacion_ant->quantity;
                }

                $total = $inv + $quantity;
                $totalant = $inv_ant - $quantity;
                Inventory::updateOrCreate(
                    ['location_id' => $location->id, 'item_id' => $item->id],
                    ['quantity' => $total]

                );

                Inventory::updateOrCreate(
                    ['location_id' => $location_old->id, 'item_id' => $item->id],
                    ['quantity' => $totalant]

                );
                $fechascan = date('Ymd', strtotime($re->created_at));
                $horascan = date('His', strtotime($re->created_at));
                $fechainfor = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                YI007::Query()->insert(
                    [
                        'I7PROD' =>  $item->item_number,
                        'I7SENO' => $serial ?? '',
                        'I7TFLG' => 'O',
                        'I7TDTE' => $fechascan,
                        'I7TTIM' => $horascan,
                        'I7TQTY' => $quantity,
                        'I7WHS' =>  $location_old->warehouse->code,
                        'I7CUSR' => 'YKMS',
                        'I7CCDT' => $fechainfor,
                        'I7CCTM' => $hora,
                    ]

                );
                YI007::Query()->insert(
                    [
                        'I7PROD' => $item->item_number,
                        'I7SENO' => $serial,
                        'I7TFLG' => 'I',
                        'I7TDTE' => $fechascan,
                        'I7TTIM' => $horascan,
                        'I7TQTY' => $quantity,
                        'I7WHS' => $location->warehouse->code,
                        'I7CUSR' => 'YKMS',
                        'I7CCDT' => $fechainfor,
                        'I7CCTM' => $hora,
                    ]
                );
            }
        }
        $scan  = input::with('item')->where([['delivery_production_id', $request->delivery_id], ['return_scan', null]])->orderBy('created_at', 'desc')->get();
        $travels = array();
        $entrega = DeliveryProduction::find($request->delivery_id);
        return view('delivery_line.scan', ['entrega' => $entrega, 'scan' => $scan, 'error' => $error, 'msg' => $message, 'location_id' => $location->id]);
    }


    public function updatebar(Request $request)
    {
        $error = 0;
        $message = '';
        $location = location::with('warehouse')->find($request->location_id);
        $serial = $request->serial;

        $prefijo_quantity = substr($request->quantity, 0, -6);
        $quantity = substr($request->quantity, -6);
        $suppier = $request->supplier;
        $item = $request->item;
        $serial = substr($serial, 1);
        $suppier = substr($suppier, 1);
        $item = substr($item, 1);
        if ($prefijo_quantity != 'Q') {
            $error = 2;
            $message = 'Cantidad es erronea';
        }
        if ($error == 0) {
            $item = DB::table('items')->whereRaw("item_number like  '" .   $item . "%'")->first();
            if ($item == false) {
                $error = 2;
                $message = 'Item no existe';
            }
        }
        if ($error == 0) {
            $serial_exist = input::where([['serial', $serial], ['supplier', $suppier]])->first();
            if ($serial_exist == null) {
                $error = 5;
                $message = 'Serial no encontrado con ese proveedor, se registra en el sistema';
            }
        }
        if ($error == 0) {
            $loc_act_id = location::with('warehouse')->whereRaw("code like'L61%'")->first();
            $serial_exist = input::where([['serial', $serial], ['supplier', $suppier]])->orderby('id', 'desc')->first();
            if ($serial_exist->location_id ==  $loc_act_id->id) {
                $error = 12;
                $message = 'Serial no se encuentra en almacen de YKM';
            }
        }
        if ($error == 0) {
            $serial_exist = input::where([['serial', $serial], ['supplier', $suppier]])->where('delivery_production_id', $request->delivery_id)->first();
            if ($serial_exist != false) {
                $error = 3;
                $message = 'serial ya fue escaneado';
            }
        }
        if ($error == 0) {
            $ultimaSal = input::where([['serial', $serial], ['supplier', $suppier]])->orderby('id', 'desc')->first();

            if ($ultimaSal->delivery_production_id != null) {

                if ($ultimaSal->return_scan == null) {
                    $error = 10;
                    $message = 'Serial ya se entrego anteriormente.';
                }
            }
        }
        $location_old = location::with('warehouse')->where('code', 'like', 'L60%')->first();
        if ($error == 0) {


            $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();
            $re = Output::create([
                'supplier' =>  $suppier,
                'serial' => $serial,
                'item_id' => $item->id,
                'item_quantity' =>  $quantity,
                'transaction_type_id' => $Transaction_type->id,
                'purchase_order' =>  'MY',
                'delivery_production_id' => $request->delivery_id,
                'location_id' => $location_old->id,
                'user_id' =>     $use = Auth::user()->id
            ]);
            $re = input::create([
                'supplier' =>  $suppier,
                'serial' => $serial,
                'item_id' => $item->id,
                'item_quantity' =>  $quantity,
                'type_consignment' =>  'MY/MZ',
                'transaction_type_id' => $Transaction_type->id,
                'delivery_production_id' => $request->delivery_id,
                'location_id' => $request->location_id,
                'user_id' =>     $use = Auth::user()->id
            ]);

            $operacion = Inventory::where('location_id', $location->id)->where('item_id', $item->id)->first();
            $operacion_ant = Inventory::where('location_id', $location_old->id)->where('item_id', $item->id)->first();
            if (is_null($operacion)) {
                $inv = 0;
            } else {
                $inv = $operacion->quantity;
            }
            if (is_null($operacion_ant)) {
                $inv_ant = 0;
            } else {
                $inv_ant = $operacion_ant->quantity;
            }

            $total = $inv + $quantity;
            $totalant = $inv_ant - $quantity;
            Inventory::updateOrCreate(
                ['location_id' => $location->id, 'item_id' => $item->id],
                ['quantity' => $total]

            );

            Inventory::updateOrCreate(
                ['location_id' => $location_old->id, 'item_id' => $item->id],
                ['quantity' => $totalant]

            );
            $fechascan = date('Ymd', strtotime($re->created_at));
            $horascan = date('His', strtotime($re->created_at));
            $fechainfor = date('Ymd', strtotime('now'));
            $hora = date('His', time());
            YI007::Query()->insert(
                [
                    'I7PROD' =>  $item->item_number,
                    'I7SENO' => $serial ?? '',
                    'I7TFLG' => 'O',
                    'I7TDTE' => $fechascan,
                    'I7TTIM' => $horascan,
                    'I7TQTY' => $quantity,
                    'I7WHS' =>  $location_old->warehouse->code,
                    'I7CUSR' => 'YKMS',
                    'I7CCDT' => $fechainfor,
                    'I7CCTM' => $hora,
                ]

            );
            YI007::Query()->insert(
                [
                    'I7PROD' => $item->item_number,
                    'I7SENO' => $serial,
                    'I7TFLG' => 'I',
                    'I7TDTE' => $fechascan,
                    'I7TTIM' => $horascan,
                    'I7TQTY' => $quantity,
                    'I7WHS' => $location->warehouse->code,
                    'I7CUSR' => 'YKMS',
                    'I7CCDT' => $fechainfor,
                    'I7CCTM' => $hora,
                ]
            );
            $message = 'Serial capturado exitosamente';
        } else {
            if ($error == 5) {

                $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();
                $re = input::create([
                    'supplier' => $suppier,
                    'serial' => $serial,
                    'item_id' => $item->id,
                    'item_quantity' =>  $quantity,
                    'type_consignment' => 'MY/MZ',
                    'transaction_type_id' => $Transaction_type->id,
                    'location_id' => $location_old->id,
                    'user_id' =>     $use = Auth::user()->id
                ]);
                //-----------------------------------------------------------------------------------
                $operacion = Inventory::where('location_id', $location_old->id)->where('item_id', $item->id)->first();
                if (is_null($operacion)) {
                    $inv = 0;
                } else {
                    $inv = $operacion->quantity;
                }

                $total = $inv + $quantity;
                $mov = Inventory::updateOrCreate(
                    ['location_id' => $location_old->id, 'item_id' => $item->id],
                    ['quantity' => $total]

                );
                //-----------------------------------------------------------
                $fechascan = date('Ymd', strtotime($re->created_at));
                $horascan = date('His', strtotime($re->created_at));
                $fechainfor = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                $loc_ant_id = location::with('warehouse')->whereRaw("code like 'L60%'")->first();
                $container = ShippingInstruction::where([['serial', $serial], ['part_no', $item->item_number]])->first();
                if ($container == null) {
                    $fecha_con = 0;
                    $hora_con = 0;
                } else {
                    $fecha_con = Carbon::parse($container->arrival_date)->format('Ymd');
                    $hora_con = Carbon::parse($container->arrival_time)->format('His');
                }
                $infor = YI007::Query()->insert(
                    [
                        'I7PROD' => $item->item_number,
                        'I7SENO' => $serial,
                        'I7TFLG' => 'A',
                        'I7TDTE' => $fechascan,
                        'I7TTIM' => $horascan,
                        'I7TQTY' =>  $quantity,
                        'I7WHS' =>   $loc_ant_id->warehouse->code,
                        'I7CUSR' => 'YKMS',
                        'I7CCDT' => $fechainfor,
                        'I7CCTM' => $hora,
                    ]

                );
                $re = Output::create([
                    'supplier' =>  $suppier,
                    'serial' => $serial,
                    'item_id' => $item->id,
                    'item_quantity' =>  $quantity,
                    'transaction_type_id' => $Transaction_type->id,
                    'delivery_production_id' => $request->delivery_id,
                    'location_id' => $location_old->id,
                    'user_id' =>     $use = Auth::user()->id
                ]);
                $re = input::create([
                    'supplier' =>  $suppier,
                    'serial' => $serial,
                    'item_id' => $item->id,
                    'item_quantity' => $quantity,
                    'type_consignment' =>  'MY/MZ',
                    'transaction_type_id' => $Transaction_type->id,
                    'delivery_production_id' => $request->delivery_id,
                    'location_id' => $request->location_id,
                    'user_id' =>     $use = Auth::user()->id
                ]);
                if ($error == 0) {
                    $message = 'serial capturado exitosamente';
                }
                $operacion = Inventory::where('location_id', $location->id)->where('item_id', $item->id)->first();
                $operacion_ant = Inventory::where('location_id', $location_old->id)->where('item_id', $item->id)->first();
                if (is_null($operacion)) {
                    $inv = 0;
                } else {
                    $inv = $operacion->quantity;
                }
                if (is_null($operacion_ant)) {
                    $inv_ant = 0;
                } else {
                    $inv_ant = $operacion_ant->quantity;
                }

                $total = $inv + $quantity;
                $totalant = $inv_ant - $quantity;
                Inventory::updateOrCreate(
                    ['location_id' => $location->id, 'item_id' => $item->id],
                    ['quantity' => $total]

                );

                Inventory::updateOrCreate(
                    ['location_id' => $location_old->id, 'item_id' => $item->id],
                    ['quantity' => $totalant]

                );
                $fechascan = date('Ymd', strtotime($re->created_at));
                $horascan = date('His', strtotime($re->created_at));
                $fechainfor = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                YI007::Query()->insert(
                    [
                        'I7PROD' =>  $item->item_number,
                        'I7SENO' => $serial ?? '',
                        'I7TFLG' => 'O',
                        'I7TDTE' => $fechascan,
                        'I7TTIM' => $horascan,
                        'I7TQTY' => $quantity,
                        'I7WHS' =>  $location_old->warehouse->code,
                        'I7CUSR' => 'YKMS',
                        'I7CCDT' => $fechainfor,
                        'I7CCTM' => $hora,
                    ]

                );
                YI007::Query()->insert(
                    [
                        'I7PROD' => $item->item_number,
                        'I7SENO' => $serial,
                        'I7TFLG' => 'I',
                        'I7TDTE' => $fechascan,
                        'I7TTIM' => $horascan,
                        'I7TQTY' => $quantity,
                        'I7WHS' => $location->warehouse->code,
                        'I7CUSR' => 'YKMS',
                        'I7CCDT' => $fechainfor,
                        'I7CCTM' => $hora,
                    ]
                );
            }
        }
        $scan  = input::with('item')->where([['delivery_production_id', $request->delivery_id], ['return_scan', null]])->orderby('id', 'desc')->get();
        $travels = array();
        $entrega = DeliveryProduction::find($request->delivery_id);
        return view('delivery_line.scanbar', ['entrega' => $entrega, 'scan' => $scan, 'error' => $error, 'msg' => $message, 'location_id' => $location->id]);
    }
    public function scanbar(Request $request)
    {
        $location = location::find($request->location_id);
        $scan  = input::with('item')->where([['delivery_production_id', $request->Delivery_id], ['return_scan', null]])->get();
        $entrega = DeliveryProduction::find($request->Delivery_id);
        return view('delivery_line.scanbar', ['entrega' => $entrega, 'scan' => $scan, 'location_id' => $location->id]);
    }
    public function scanqr(Request $request)
    {
        $location = location::find($request->location_id);
        $scan  = input::with('item')->where([['delivery_production_id', $request->Delivery_id], ['return_scan', null]])->get();
        $entrega = DeliveryProduction::find($request->Delivery_id);

        return view('delivery_line.scan', ['entrega' => $entrega, 'scan' => $scan, 'location_id' => $location->id]);
    }
    public function export(Request $request)
    {

        $doc = new ExportsDeliveryExport($request->delivery_id);

        return Excel::download($doc, 'report.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        //    return (new ExportsDeliveryExport($request->delivery_id),'invoices.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\deliveryproductions  $deliveryproductions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //     $error = 0;
        //     $message = ' ';
        //     $itemin = input::with('item')->find($request->serial_id);
        //     if ($itemin->return_scan != 1) {
        //         $itemout = output::where([['serial ', 'like', $itemin->serial], ['delivery_production_id', $itemin->delivery_production_id]])->first();
        //         $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();
        //         $location_old = location::with('warehouse')->where('code', 'like', 'L12%')->first();
        //         $location_new = location::with('warehouse')->where('code', 'like', 'L60%')->first();
        //         $fecha = Carbon::parse(now())->format('Ymd  His');
        //         SELF::inventario($itemin->serial, $itemin->item_id, $itemin->item->item_number, $location_old->id, $itemin->item_quantity, $location_old->id,   $fecha, $location_old->warehouse->code, $location_new->warehouse->code);
        //         // dd($request->serial_id, $itemin ,$itemin->serial, $itemin->id, $itemin->item->item_number, $itemin->location_id, $itemin->item_quantity, $location_old->id,   $fecha, $location_old->warehouse->code,$location_new->warehouse->code);
        //         input::where('id', $request->serial_id)->update(['return_scan' => 1]);
        //         output::where('id', $itemout->id)->update(['return_scan' => 1]);
        //         // $reout = output::where([['delivery_production_id', $itemin->delivery_id], ['serial', $itemin->serial], ['item_id', $itemin->item_id]])->update(['return' => true]);
        //         $error = 1;
        //         $message = 'serial eliminado de la entrega actual ';
        //         $scan  = input::with('item')->where([['delivery_production_id', $request->delivery_id], ['return_scan', null]])->simplePaginate(10);
        //         $travels = array();
        //         $entrega = DeliveryProduction::find($request->delivery_id);
        //         return view('delivery_line.scan', ['entrega' => $entrega, 'scan' => $scan, 'error' => $error, 'msg' => $message,]);
        //     } else {
        //         $entrega = DeliveryProduction::find($request->delivery_id);
        //         $scan  = input::with('item')->where([['delivery_production_id', $request->delivery_id], ['return_scan', null]])->simplePaginate(10);

        //         return view('delivery_line.scan', ['entrega' => $entrega, 'scan' => $scan]);
        //     }
    }
}
