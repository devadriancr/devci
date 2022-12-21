<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\DeliveryProduction;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Output;
use App\Models\item;
use App\Models\input;
use App\Models\YI007;
use Illuminate\Support\Facades\DB;
use App\Models\transactiontype;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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
        $travels = DeliveryProduction::get();
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
        $No = DeliveryProduction::create([
            'control_number' => $request->number_control,
            'location_id' => $location->id,
            'finish'=>1
        ]);
        $scan =  input::where('delivery_production_id',$No->id)->simplePaginate(10);
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
        $scan  = input::with('item')->where('delivery_production_id', $request->Delivery_id)->get();

        $loc_ant_id = location::with('warehouse')->whereRaw("TRIM(code)='L60'")->first();
        $loc_act_id = location::with('warehouse')->whereRaw("TRIM(code)='L12'")->first();
        foreach ($scan as $scans) {

            self::inventario($scans->serial, $scans->item_id, $scans->item->item_number, $scans->location_id, $scans->item_quantity, $loc_ant_id->id, $scans->created_at, $loc_ant_id->warehouse->code, $loc_act_id->warehouse->code);
        }
        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU02.YIN151C";
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
    public function inventario($serial, $item, $number_item, $loc, $cantidad, $loc_ant, $fechahora, $WH, $wh_act)
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
                'I7SENO' => $serial ?? '',
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\deliveryproductions  $deliveryproductions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $error = 0;
        $location = location::find($request->location_id);
        $cadena = explode(",", $request->serial);
        if (count($cadena) != 27) {
            $error = 1;
            $message = 'ESCANEO INCORRECTO';
        }
        if ($error == 0) {
            $item = DB::table('items')->whereRaw("TRIM(item_number)= '" .  end($cadena) . "'")->first();
            if ($item == false) {
                $error = 2;
                $message = 'Item no existe';
            }
        }
        if ($error == 0) {
            $serial_exist = input::where('serial', $cadena[13],)->where('container_id', '!=', null)->first();
            if ($serial_exist == null) {
                $error = 5;
                $message = 'Serial no encontrado en shipping';
            }
        }
        if ($error == 0) {
            $loc_act_id = location::with('warehouse')->whereRaw("TRIM(code)='L61'")->first();
            $serial_exist = input::where('serial', $cadena[13])->orderby('id', 'desc')->first();
            if ($serial_exist->location_id ==  $loc_act_id->id) {
                $error = 12;
                $message = 'Serial no se encuentra en almacen de YKM';
            }
        }
        if ($error == 0) {
            $serial_exist = input::where('serial', $cadena[13])->where('delivery_production_id', $request->delivery_id)->first();
            if ($serial_exist != false) {
                $error = 3;
                $message = 'serial ya fue escaneado';
            }
        }

        if ($error == 0) {
            $ultimaSal = input::where('serial', $cadena[13])->orderby('id', 'desc')->first();
            if ($ultimaSal->delivery_production_id != null) {
                $error = 10;
                $message = 'Serial ya se entrego anteriormente.';
            }
        }





        if ($error == 0) {
            $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();
            $re = Output::create([
                'supplier' =>  $cadena[11],
                'serial' => $cadena[13],
                'item_id' => $item->id,
                'item_quantity' =>  $cadena[10],
                'transaction_type_id' => $Transaction_type->id,
                'delivery_production_id' => $request->delivery_id,
                'location_id' => $request->location_id
            ]);
            $re = input::create([
                'supplier' =>  $cadena[11],
                'serial' => $cadena[13],
                'item_id' => $item->id,
                'item_quantity' =>  $cadena[10],
                'transaction_type_id' => $Transaction_type->id,
                'delivery_production_id' => $request->delivery_id,
                'location_id' => $request->location_id
            ]);
            $message = 'Serial capturado exitosamente';
        }
        $scan  = input::with('item')->where('delivery_production_id', $request->delivery_id)->simplePaginate(10);
        $travels = array();
        $entrega = DeliveryProduction::find($request->delivery_id);
        return view('delivery_line.scan', ['entrega' => $entrega, 'scan' => $scan, 'error' => $error, 'msg' => $message, 'location_id' => $location->id]);
    }
    public function export(Request $request)
    {
        return Excel::download(new ExportsDeliveryExport($request->delivery_id), 'report.xlsx');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\deliveryproductions  $deliveryproductions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $itemin=input::find($request->serial_id);
        $itemin->delete();
        $itemout=output::where([['delivery_production_id', $request->delivery_id], ['serial', $request->serial]])->delete();

        $scan  = input::with('item')->where('delivery_production_id', $request->delivery_id)->simplePaginate(10);
        $travels = array();
        $entrega = DeliveryProduction::find($request->delivery_id);
        $error = 1;
        $message = 'serial eliminado de la entrega actual ';
        return view('delivery_line.scan', ['entrega' => $entrega, 'scan' => $scan, 'error' => $error, 'msg' => $message, ]);
    }
}
