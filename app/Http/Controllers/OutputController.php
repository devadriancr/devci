<?php

namespace App\Http\Controllers;

use App\Models\Output;
use App\Models\item;
use App\Models\input;
use App\Models\transactiontype;
use App\Models\travel;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\Warehouse;
use App\Models\YI007;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\YH003;
use App\Models\ShippingInstruction;
use Carbon\Carbon;

class OutputController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = strtoupper($request->search) ?? '';

        $outputs = Output::query()
            ->join('items', 'outputs.item_id', '=', 'items.id')
            ->join('item_classes', 'items.item_class_id', '=', 'item_classes.id')
            ->where('item_classes.code', 'LIKE', 'S1%')
            ->orWhere('outputs.serial', 'LIKE', '%' . $search . '%')
            ->orWhere('items.item_number', 'LIKE', '%' . $search . '%')
            ->orderBy('items.created_at', 'DESC')
            ->paginate(10);

        return view('output.output', ['outputs' => $outputs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create1(Request $request)
    {
        $error = 0;
        $location = location::with('warehouse')->find($request->location_id);
        $cadena = explode(",", $request->serial);

        if (count($cadena) != 27) {
            $error = 8;

            $message = 'ESCANEO INCORRECTO';
        }
        if ($error == 0) {
            $item = DB::table('items')->whereRaw("item_number like '" .  end($cadena) . "%'")->first();
            if ($item == false) {
                $error = 2;
                $message = 'Item no existe';
            }
        }
        if ($error == 0) {
            $serial_exist = input::where([['serial', $cadena[13]], ['supplier', $cadena[11]], ['item_id', $item->id]])->orderby('id', 'desc')->first();
            if ($serial_exist == null) {
                $error = 5;
                $message = 'Serial no encontrado en shipping se ha dado de alta';
            }
            if ($error == 0) {
                if ($serial_exist->delivery_production_id != null) {
                    $error = 11;
                    $message = 'Serial ya fue entregado a linea de produccion ';
                }
            }
            if ($error == 0) {
                if ($serial_exist->travel_id == $request->travel_id) {
                    $error = 3;
                    $message = 'serial ya fue  escaneado';
                } else {
                    $scan_ac = input::where([['serial', $cadena[13]], ['supplier', $cadena[11]], ['item_id', $item->id], ['travel_id', $request->travel_id]])->orderby('id', 'desc')->first();
                    if ($scan_ac != null) {
                        $error = 3;
                        $message = 'serial ya fue  escaneado';
                    }
                }
            }
            if ($error == 0) {
                if ($serial_exist != null) {
                    if ($serial_exist->location_id == $request->location_id) {
                        $error = 4;
                        $message = 'serial ya existente en el almacen actual ';
                    }
                }
            }
        }
        if ($error <= 1) {
            $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();
            if ($location->code == 'L61       ') {
                $location_old  = location::with('warehouse')->where('code', 'like', '%L60%')->first();
                $invenoti = inventory::where([['item_id', $item->id], ['location_id', $location_old->id]])->first();
                if ($invenoti != null) {
                    $total = $invenoti->opening_balance + $invenoti->quantity;
                } else {
                    $total = 0;
                }
                if ($item->safety_stock > $total) {
                    $error = 1;
                    $message = 'escaneo correcto inventario menor a safety stock';
                }
            } else {
                $location_old = location::where('code', 'like', '%L61%')->first();
            }
            if ($error == 0) {
                $message = 'serial capturado exitosamente';
            }
            Output::create([
                'supplier' =>  $cadena[11],
                'serial' => $cadena[13],
                'item_id' => $item->id,
                'item_quantity' =>  $cadena[10],
                'transaction_type_id' => $Transaction_type->id,
                'travel_id' => $request->travel_id,
                'location_id' => $location_old->id,
                'user_id' =>     Auth::user()->id
            ]);
            $re = input::create([
                'supplier' =>  $cadena[11],
                'serial' => $cadena[13],
                'item_id' => $item->id,
                'item_quantity' =>  $cadena[10],
                'type_consignment' => "MY/MZ",
                'transaction_type_id' => $Transaction_type->id,
                'travel_id' => $request->travel_id,
                'location_id' => $request->location_id,
                'user_id' =>      Auth::user()->id
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
            $total = $inv +  $cadena[10];
            $totalant = $inv_ant -  $cadena[10];
            $mov = Inventory::updateOrCreate(
                ['location_id' => $location->id, 'item_id' => $item->id],
                ['quantity' => $total]
            );
            $mov = Inventory::updateOrCreate(
                ['location_id' => $location_old->id, 'item_id' => $item->id],
                ['quantity' => $totalant]
            );
            $fechascan = date('Ymd', strtotime($re->created_at));
            $horascan = date('His', strtotime($re->created_at));
            $fechainfor = date('Ymd', strtotime('now'));
            $hora = date('His', time());
            $infor = YI007::Query()->insert(
                [
                    'I7PROD' => $item->item_number,
                    'I7SENO' => $cadena[13],
                    'I7TFLG' => 'O',
                    'I7TDTE' => $fechascan,
                    'I7TTIM' => $horascan,
                    'I7TQTY' => $cadena[10],
                    'I7WHS' =>  $location_old->warehouse->code,
                    'I7CUSR' => 'YKMS',
                    'I7CCDT' => $fechainfor,
                    'I7CCTM' => $hora,
                ]

            );
            $infor = YI007::Query()->insert(
                [
                    'I7PROD' => $item->item_number,
                    'I7SENO' => $cadena[13],
                    'I7TFLG' => 'I',
                    'I7TDTE' => $fechascan,
                    'I7TTIM' => $horascan,
                    'I7TQTY' => $cadena[10],
                    'I7WHS' =>  $location->warehouse->code,
                    'I7CUSR' => 'YKMS',
                    'I7CCDT' => $fechainfor,
                    'I7CCTM' => $hora,
                ]

            );
        } else {

            if ($error == 5) {
                $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();
                if ($location->code == 'L61       ') {
                    $location_old  = location::with('warehouse')->where('code', 'like', '%L60%')->first();
                    $invenoti = inventory::where([['item_id', $item->id], ['location_id', $location_old->id]])->first();
                } else {
                    $location_old = location::where('code', 'like', '%L61%')->first();
                }
                $re = input::create([
                    'supplier' =>  $cadena[11],
                    'serial' => $cadena[13],
                    'item_id' => $item->id,
                    'item_quantity' =>  $cadena[10],
                    'transaction_type_id' => $Transaction_type->id,
                    'location_id' =>  $location_old->id,
                    'user_id' =>   Auth::user()->id
                ]);
                $operacion = Inventory::where('location_id', $location_old->id)->where('item_id', $item->id)->first();
                if (is_null($operacion)) {
                    $inv = 0;
                } else {
                    $inv = $operacion->quantity;
                }

                $total = $inv +  $cadena[10];

                $mov = Inventory::updateOrCreate(
                    ['location_id' => $location_old->id, 'item_id' => $item->id],
                    ['quantity' => $total]
                );

                $fechascan = date('Ymd', strtotime($re->created_at));
                $horascan = date('His', strtotime($re->created_at));
                $fechainfor = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                $infor = YI007::Query()->insert(
                    [
                        'I7PROD' => $item->item_number,
                        'I7SENO' => $cadena[13],
                        'I7TFLG' => 'A',
                        'I7TDTE' => $fechascan,
                        'I7TTIM' => $horascan,
                        'I7TQTY' =>  $cadena[10],
                        'I7WHS' =>   $location_old->warehouse->code,
                        'I7CUSR' => 'YKMS',
                        'I7CCDT' => $fechainfor,
                        'I7CCTM' => $hora,
                    ]
                );
                if ($error == 0) {
                    $message = 'serial capturado exitosamente';
                }

                Output::create([
                    'supplier' =>  $cadena[11],
                    'serial' => $cadena[13],
                    'item_id' => $item->id,
                    'item_quantity' =>  $cadena[10],
                    'transaction_type_id' => $Transaction_type->id,
                    'travel_id' => $request->travel_id,
                    'location_id' => $location_old->id,
                    'user_id' =>     Auth::user()->id
                ]);
                $re = input::create([
                    'supplier' =>  $cadena[11],
                    'serial' => $cadena[13],
                    'item_id' => $item->id,
                    'item_quantity' =>  $cadena[10],
                    'transaction_type_id' => $Transaction_type->id,
                    'travel_id' => $request->travel_id,
                    'location_id' => $request->location_id,
                    'user_id' =>      Auth::user()->id
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
                $total = $inv +  $cadena[10];
                $totalant = $inv_ant -  $cadena[10];
                $mov = Inventory::updateOrCreate(
                    ['location_id' => $location->id, 'item_id' => $item->id],
                    ['quantity' => $total]
                );
                $mov = Inventory::updateOrCreate(
                    ['location_id' => $location_old->id, 'item_id' => $item->id],
                    ['quantity' => $totalant]
                );
                $fechascan = date('Ymd', strtotime($re->created_at));
                $horascan = date('His', strtotime($re->created_at));
                $fechainfor = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                $infor = YI007::Query()->insert(
                    [
                        'I7PROD' => $item->item_number,
                        'I7SENO' => $cadena[13],
                        'I7TFLG' => 'O',
                        'I7TDTE' => $fechascan,
                        'I7TTIM' => $horascan,
                        'I7TQTY' => $cadena[10],
                        'I7WHS' =>  $location_old->warehouse->code,
                        'I7CUSR' => 'YKMS',
                        'I7CCDT' => $fechainfor,
                        'I7CCTM' => $hora,
                    ]

                );
                $infor = YI007::Query()->insert(
                    [
                        'I7PROD' => $item->item_number,
                        'I7SENO' => $cadena[13],
                        'I7TFLG' => 'I',
                        'I7TDTE' => $fechascan,
                        'I7TTIM' => $horascan,
                        'I7TQTY' => $cadena[10],
                        'I7WHS' =>  $location->warehouse->code,
                        'I7CUSR' => 'YKMS',
                        'I7CCDT' => $fechainfor,
                        'I7CCTM' => $hora,
                    ]

                );
            }
        }
        $scan  = input::with('item')->where('travel_id', $request->travel_id)->orderBy('created_at', 'desc')->get();
        $locations = location::find($request->location_id);
        $travels = travel::find($request->travel_id);

        return view('Output.scan', ['travels' => $travels, 'scan' => $scan, 'error' => $error, 'msg' => $message, 'location_id' => $locations]);
    }
    public function createscan(Request $request)
    {
        $serial = $request->serial;
        $quantity = $request->quantity;
        $suppier = $request->supplier;
        $item_n = $request->item;
        $quantity  = substr($quantity, 1);
        $serial = substr($serial, 1);
        $suppier = substr($suppier, 1);
        $item_n = substr($item_n, 1);
        $error = 0;
        $location = location::with('warehouse')->find($request->location_id);
        if ($error == 0) {
            $item = DB::table('items')->whereRaw("item_number like '" . $item_n . "%'")->first();
            if ($item == false) {
                $error = 2;
                $message = 'Item no existe';
            }
        }
        if ($error == 0) {
            $serial_exist = input::where([['serial', $serial], ['supplier', $suppier], ['item_id', $item->id]])->orderby('id', 'desc')->first();
            if ($serial_exist == null) {
                $error = 5;
                $message = 'Serial no encontrado en shipping se ha dado de alta';
            }
            if ($error == 0) {
                if ($serial_exist->delivery_production_id != null) {
                    $error = 11;
                    $message = 'Serial ya fue entregado a linea de produccion ';
                }
            }
            if ($error == 0) {

                if ($serial_exist->travel_id == $request->travel_id) {
                    $error = 3;
                    $message = 'serial ya fue  escaneado';
                } else {
                    $scan_ac = input::where([['serial', $serial], ['supplier', $suppier], ['item_id', $item->id], ['travel_id', $request->travel_id]])->orderby('id', 'desc')->first();
                    if ($scan_ac != null) {
                        $error = 3;
                        $message = 'serial ya fue  escaneado';
                    }
                }
            }
            if ($error == 0) {
                if ($serial_exist != null) {
                    if ($serial_exist->location_id == $request->location_id) {
                        $error = 4;
                        $message = 'serial ya existente en el almacen actual ';
                    }
                }
            }
        }
        $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();

        if ($error <= 1) {
            if ($location->code == 'L61       ') {
                $location_old  = location::with('warehouse')->where('code', 'like', '%L60%')->first();
                $invenoti = inventory::where([['item_id', $item->id], ['location_id', $location_old->id]])->first();
                if ($invenoti != null) {
                    $total = $invenoti->opening_balance + $invenoti->quantity;
                } else {
                    $total = 0;
                }
                if ($item->safety_stock > $total) {
                    $error = 1;
                    $message = 'escaneo correcto inventario menor a safety stock';
                }
            } else {
                $location_old = location::where('code', 'like', '%L61%')->first();
            }
             // -------------------------------ingresa los movimientos de entrada y salidad  -------------------

            Output::create([
                'supplier' =>  $suppier,
                'serial' => $serial,
                'item_id' => $item->id,
                'item_quantity' =>  $quantity,
                'transaction_type_id' => $Transaction_type->id,
                'travel_id' => $request->travel_id,
                'location_id' => $location_old->id,
                'user_id' => Auth::user()->id
            ]);
            $re = input::create([
                'supplier' =>  $suppier,
                'serial' => $serial,
                'item_id' => $item->id,
                'item_quantity' =>  $quantity,
                'transaction_type_id' => $Transaction_type->id,
                'travel_id' => $request->travel_id,
                'location_id' => $request->location_id,
                'user_id' => Auth::user()->id
            ]);
            if ($error == 0) {
                $message = 'serial capturado exitosamente';
            }
              // -------------------------------ingresa los movimientos de entrada y salidad inventario-------------------

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
            $total = $inv +  $quantity;
            $totalant = $inv_ant -  $quantity;
            $mov = Inventory::updateOrCreate(
                ['location_id' => $location->id, 'item_id' => $item->id],
                ['quantity' => $total]
            );
            $mov = Inventory::updateOrCreate(
                ['location_id' => $location_old->id, 'item_id' => $item->id],
                ['quantity' => $totalant]
            );
              // -------------------------------ingresa los movimientos de entrada y salidad  Infor-------------------

            $fechascan = date('Ymd', strtotime($re->created_at));
            $horascan = date('His', strtotime($re->created_at));
            $fechainfor = date('Ymd', strtotime('now'));
            $hora = date('His', time());
            $infor = YI007::Query()->insert(
                [
                    'I7PROD' => $item->item_number,
                    'I7SENO' => $serial,
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
            $infor = YI007::Query()->insert(
                [
                    'I7PROD' => $item->item_number,
                    'I7SENO' => $serial,
                    'I7TFLG' => 'I',
                    'I7TDTE' => $fechascan,
                    'I7TTIM' => $horascan,
                    'I7TQTY' => $quantity,
                    'I7WHS' =>  $location->warehouse->code,
                    'I7CUSR' => 'YKMS',
                    'I7CCDT' => $fechainfor,
                    'I7CCTM' => $hora,
                ]

            );
        } else {
            if ($error == 5) {
                if ($location->code == 'L61       ') {
                    $location_old  = location::with('warehouse')->where('code', 'like', '%L60%')->first();
                    $invenoti = inventory::where([['item_id', $item->id], ['location_id', $location_old->id]])->first();
                    if ($invenoti != null) {
                        $total = $invenoti->opening_balance + $invenoti->quantity;
                    } else {
                        $total = 0;
                    }
                    if ($item->safety_stock > $total) {
                        $error = 1;
                        $message = 'escaneo correcto inventario menor a safety stock';
                    }
                } else {
                    $location_old = location::where('code', 'like', '%L61%')->first();
                }
                $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();
                // -------------------------------ingresa primer registro si no esta -------------------
                $re = input::create([
                    'supplier' => $suppier,
                    'serial' => $serial,
                    'item_id' => $item->id,
                    'item_quantity' =>  $quantity,
                    'transaction_type_id' => $Transaction_type->id,
                    'location_id' => $location_old->id,
                    'user_id' => Auth::user()->id
                ]);
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

                $fechascan = date('Ymd', strtotime($re->created_at));
                $horascan = date('His', strtotime($re->created_at));
                $fechainfor = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                $infor = YI007::Query()->insert(
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
                // -------------------------------ingresa los movimientos de entrada y salidad  -------------------
                Output::create([
                    'supplier' =>  $suppier,
                    'serial' => $serial,
                    'item_id' => $item->id,
                    'item_quantity' =>  $quantity,
                    'transaction_type_id' => $Transaction_type->id,
                    'travel_id' => $request->travel_id,
                    'location_id' => $location_old->id,
                    'user_id' =>     Auth::user()->id
                ]);
                $re = input::create([
                    'supplier' => $suppier,
                    'serial' => $serial,
                    'item_id' => $item->id,
                    'item_quantity' =>  $quantity,
                    'transaction_type_id' => $Transaction_type->id,
                    'travel_id' => $request->travel_id,
                    'location_id' => $request->location_id,
                    'user_id' =>  Auth::user()->id
                ]);
                if ($error == 0) {
                    $message = 'serial capturado exitosamente';
                }
                  // -------------------------------ingresa los movimientos de inventario de suma y resta   -------------------
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
                $total = $inv +  $quantity;
                $totalant = $inv_ant -  $quantity;

                $mov = Inventory::updateOrCreate(
                    ['location_id' => $location->id, 'item_id' => $item->id],
                    ['quantity' => $total]
                );
                $mov = Inventory::updateOrCreate(
                    ['location_id' => $location_old->id, 'item_id' => $item->id],
                    ['quantity' => $totalant]
                );

                // -------------------------------ingresa los movimientos de entrada y salidad a ifnfor  -------------------

                $fechascan = date('Ymd', strtotime($re->created_at));
                $horascan = date('His', strtotime($re->created_at));
                $fechainfor = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                $infor = YI007::Query()->insert(
                    [
                        'I7PROD' => $item->item_number,
                        'I7SENO' => $serial,
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
                $infor = YI007::Query()->insert(
                    [
                        'I7PROD' => $item->item_number,
                        'I7SENO' => $serial,
                        'I7TFLG' => 'I',
                        'I7TDTE' => $fechascan,
                        'I7TTIM' => $horascan,
                        'I7TQTY' => $quantity,
                        'I7WHS' =>  $location->warehouse->code,
                        'I7CUSR' => 'YKMS',
                        'I7CCDT' => $fechainfor,
                        'I7CCTM' => $hora,
                    ]

                );
            }
        }
        $scan  = input::with('item')->where('travel_id', $request->travel_id)->orderBy('created_at', 'desc')->GET();
        $locations = location::find($request->location_id);
        $travels = travel::find($request->travel_id);
        return view('Output.scanbar', ['travels' => $travels, 'scan' => $scan, 'error' => $error, 'msg' => $message, 'location_id' => $locations]);
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

    public function scanbar(Request $request)
    {
        $scan  = output::with('item')->where('travel_id', $request->travel_id)->simplePaginate(10);
        $locations = location::find($request->location_id);
        $travels = travel::find($request->travel_id);

        return view('output.scanbar', ['travels' => $travels, 'scan' => $scan,  'location_id' => $locations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $travel = Travel::with('location')->find($request->travel_id);


        if ($travel->location->code == 'L61       ') {
            $scan  = output::with('item')->where('travel_id', $request->travel_id)->get();
        } else {
            $scan  = input::with('item')->where('travel_id', $request->travel_id)->get();
        }
        $msg = '';
        $locations = location::get();
        return view('Output.scan', ['travels' => $travel, 'scan' => $scan, 'msg' => $msg, 'locations' => $locations]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function show(Output $output)
    {
    }
    public function search(Request $request)
    {
        if ($request->serial != null) {
            $serial = $request->serial ?? 0;

            $cont = strlen($serial);
            $error = 0;
            $msg = '';

            switch ($cont) {
                case 10:
                    $serial = substr($serial, 1);
                    break;
                case 9:
                    $serial = $serial;
                    break;
                default:
                    if ($cont < 9) {
                        $serial = 0;
                        $error = 1;
                        $msg = 'Escaneo incorrecto';
                    } else {
                        $cadena = explode(",", $serial);
                        $serial = $cadena[13];
                    }
                    break;
            }

            $regin = input::with('item', 'location', 'container')->where('serial', $serial)->orderby('id', 'desc')->simplePaginate(10);
            $total = count($regin);
            if (count($regin) == 0) {
                $error = 2;
                $msg = 'Serial no encontrado';
            }
            if ($serial == 0) {
                $error = 0;
                $msg = '';
            }
        } else {
            $regin = null;
            $error = 0;
            $msg = '';
            $total = 0;
        }

        return view('Output.search', ['in' => $regin, 'error' => $error, 'msg' => $msg, 'total' => $total]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function return(Request $request)
    {
        $reg = input::with('item', 'location')->find($request->id);
        $location_new = location::where('code', 'like', '%L60%')->first();
        Output::create([
            'supplier' =>   $reg->suppier,
            'serial' => $reg->serial,
            'item_id' => $reg->item_id,
            'item_quantity' =>  $reg->item_quantity,
            'transaction_type_id' => $reg->transaction_type_id,
            'location_id' =>  $reg->location_id,
            'user_id' =>     Auth::user()->id
        ]);
        input::create([
            'supplier' => $reg->suppier,
            'serial' => $reg->serial,
            'item_id' => $reg->item_id,
            'item_quantity' =>  $reg->item_quantity,
            'transaction_type_id' => $reg->transaction_type_id,
            'location_id' =>  $location_new->id,
            'user_id' => Auth::user()->id
        ]);
        $fechascan = date('Ymd', strtotime($reg->created_at));
        $horascan = date('His', strtotime($reg->created_at));
        $fechainfor = date('Ymd', strtotime('now'));
        $hora = date('His', time());
        $use = Auth::user()->user_infor ?? 'ykms';
        $loc_ant_id = location::with('warehouse')->whereRaw("code like 'L60%'")->first();
        $loc_new_id = location::with('warehouse')->whereRaw("code like 'L12%'")->first();
        $infor = YI007::Query()->insert(
            [
                'I7PROD' => $reg->item->item_number,
                'I7SENO' => $reg->serial,
                'I7TFLG' => 'I',
                'I7TDTE' => $fechascan,
                'I7TTIM' => $horascan,
                'I7TQTY' => $reg->item_quantity,
                'I7WHS' => $loc_new_id->warehouse->code,
                'I7CUSR' => 'YKMS',
                'I7CCDT' => $fechainfor,
                'I7CCTM' => $hora,
            ]

        );
        $infor = YI007::Query()->insert(
            [
                'I7PROD' => $reg->item->item_number,
                'I7SENO' => $reg->serial,
                'I7TFLG' => 'O',
                'I7TDTE' => $fechascan,
                'I7TTIM' => $horascan,
                'I7TQTY' => $reg->item_quantity,
                'I7WHS' => $loc_ant_id->warehouse->code,
                'I7CUSR' => 'YKMS',
                'I7CCDT' => $fechainfor,
                'I7CCTM' => $hora,
            ]

        );

        $regin = input::with('item', 'location', 'container')->where('serial', $reg->serial)->orderby('id', 'desc')->simplePaginate(10);
        $error = 1;
        $msg = 'se a regresado el material a W60';
        $total = count($regin);
        return view('Output.search', ['in' => $regin, 'error' => $error, 'msg' => $msg, 'total' => $total]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Output $output)
    {

        $travel = Travel::find($request->travel_id);
        // if ($travel->location->code == 'L61       ') {
        //     $operador = 'O';
        //     $loc_ant = 'L60       ';
        // } else {

        //     $operador = 'I';
        //     $loc_ant = 'L61       ';
        // }
        // $scan  = input::with('item')->where('travel_id', $request->travel_id)->get();
        // $loc_ant_id = location::with('warehouse')->where('code', $loc_ant)->first();
        // $loc_act_id = location::with('warehouse')->where('id', $travel->location->id)->first();
        // foreach ($scan as $scans) {
        //     self::inventario($scans->serial, $scans->item_id, $scans->item->item_number, $scans->location_id, $operador, $scans->item_quantity, $loc_ant_id->id, $scans->created_at, $loc_ant_id->warehouse->code, $loc_act_id->warehouse->code);
        // }
        // $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        // $query = "CALL LX834OU02.YIN151C";
        // live
        // $query = "CALL LX834OU.YIN151C";
        // $result = odbc_exec($conn, $query);
        Travel::updateOrCreate(
            ['id' => $request->travel_id],
            ['finish' => 1]
        );


        return redirect()->route('Travel.index');
    }


    public function inventario($serial, $item, $number_item, $loc,  $cantidad, $loc_ant, $fechahora, $WH, $wh_act)
    {

        $operacion = Inventory::where('location_id', $loc)->where('item_id', $item)->first();
        $operacion_ant = Inventory::where('location_id', $loc_ant)->where('item_id', $item)->first();
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

        $total = $inv + $cantidad;
        $totalant = $inv_ant - $cantidad;

        $mov = Inventory::updateOrCreate(
            ['location_id' => $loc, 'item_id' => $item],
            ['quantity' => $total]

        );

        $mov = Inventory::updateOrCreate(
            ['location_id' => $loc_ant, 'item_id' => $item],
            ['quantity' => $totalant]

        );

        $fechascan = date('Ymd', strtotime($fechahora));
        $horascan = date('His', strtotime($fechahora));
        $fechainfor = date('Ymd', strtotime('now'));
        $hora = date('His', time());
        $use = Auth::user()->user_infor ?? 'ykms';
        $infor = YI007::Query()->insert(
            [
                'I7PROD' => $number_item,
                'I7SENO' => $serial,
                'I7TFLG' => 'O',
                'I7TDTE' => $fechascan,
                'I7TTIM' => $horascan,
                'I7TQTY' => $cantidad,
                'I7WHS' =>  $WH,
                'I7CUSR' => 'YKMS',
                'I7CCDT' => $fechainfor,
                'I7CCTM' => $hora,
            ]

        );
        $infor = YI007::Query()->insert(
            [
                'I7PROD' => $number_item,
                'I7SENO' => $serial,
                'I7TFLG' => 'I',
                'I7TDTE' => $fechascan,
                'I7TTIM' => $horascan,
                'I7TQTY' => $cantidad,
                'I7WHS' => $wh_act,
                'I7CUSR' => 'YKMS',
                'I7CCDT' => $fechainfor,
                'I7CCTM' => $hora,
            ]

        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $itemin = input::where([['travel_id', $request->travel_id], ['serial', $request->serial]])->delete();
        $itemout = output::where([['travel_id', $request->travel_id], ['serial', $request->serial]])->delete();
        $scan  = input::with('item')->where('travel_id', $request->travel_id)->get();
        $entrega = travel::with('location', 'orderinformation')->find($request->travel_id);
        $error = 1;
        $message = 'serial eliminado del viaje actual ';
        return view('output.index', ['travels' => $entrega, 'scan' => $scan, 'error' => $error, 'msg' => $message,]);
    }
}
