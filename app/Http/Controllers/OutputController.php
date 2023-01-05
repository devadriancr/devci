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
            ->where('items.item_number', 'LIKE', '%' . $search . '%')
            ->orderBy('items.created_at', 'DESC')
            ->paginate(10);

        return view('output.output', ['outputs' => $outputs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $error = 0;
        $location = location::find($request->location_id);
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
            $serial_exist = input::where([['serial', $cadena[13]], ['supplier', $cadena[11]], ['travel_id', null]])->first();
            if ($serial_exist == null) {
                $error = 5;
                $message = 'Serial no encontrado en shipping';
            }
        }
        if ($error == 0) {
            $serial_exist = input::where([['serial', $cadena[13]], ['supplier', $cadena[11]]])->where('delivery_production_id', '!=', null)->first();
            if ($serial_exist == true) {
                $error = 11;
                $message = 'Serial ya fue entregado a linea de produccion ';
            }
        }

        if ($error == 0) {
            $serial_exist = input::where([['serial', $cadena[13]], ['supplier', $cadena[11]]])->where('travel_id', $request->travel_id)->first();
            if ($serial_exist != false) {
                $error = 3;
                $message = 'serial ya fue  escaneado';
            }
        }

        if ($error == 0) {
            $ultimaEnt = input::where([['serial', $cadena[13]], ['supplier', $cadena[11]]])->orderby('id', 'desc')->first();
            if ($ultimaEnt != null) {
                if ($ultimaEnt->location_id == $request->location_id) {
                    $error = 4;
                    $message = 'serial ya existente en el almacen actual ';
                }
            }
        }

        if ($error == 0) {

            $location = location::where('code', 'like', '%L60%')->first();
            $safetystock = item::whereraw("item_number like '" . end($cadena) . "%'")->first();

            $invenoti = inventory::where([['item_id', $safetystock->id], ['location_id', $location->id]])->first();
            if ($invenoti != null) {
                $total = $invenoti->opening_balance + $invenoti->quantity;
            } else {
                $total = 0;
            }

            if ($safetystock->safety_stock > $total) {
                $error = 1;
                $message = 'inventario menor a safety stock';
            }
        }
        $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();
        if ($error <= 1) {
            if ($location->code == 'L61       ') {
                $location_old = location::where('code', 'like', '%L60%')->first();
            } else {
                $location_old = location::where('code', 'like', '%L61%')->first();
            }
            Output::create([
                'supplier' =>  $cadena[11],
                'serial' => $cadena[13],
                'item_id' => $item->id,
                'item_quantity' =>  $cadena[10],
                'transaction_type_id' => $Transaction_type->id,
                'travel_id' => $request->travel_id,
                'location_id' => $location_old->id,
                'user_id' =>     $use = Auth::user()->id
            ]);
            input::create([
                'supplier' =>  $cadena[11],
                'serial' => $cadena[13],
                'item_id' => $item->id,
                'item_quantity' =>  $cadena[10],
                'transaction_type_id' => $Transaction_type->id,
                'travel_id' => $request->travel_id,
                'location_id' => $request->location_id,
                'user_id' =>     $use = Auth::user()->id
            ]);
            if ($error == 0) {
                $message = 'serial capturado exitosamente';
            }
        }


        $scan  = output::with('item')->where('travel_id', $request->travel_id)->simplePaginate(10);
        $locations = location::find($request->location_id);
        $travels = travel::find($request->travel_id);

        return view('Output.index', ['travels' => $travels, 'scan' => $scan, 'error' => $error, 'msg' => $message, 'location_id' => $locations]);
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
            $scan  = output::with('item')->where('travel_id', $request->travel_id)->simplePaginate(10);
        } else {
            $scan  = input::with('item')->where('travel_id', $request->travel_id)->simplePaginate(10);
        }
        $msg = '';
        $locations = location::get();
        return view('Output.index', ['travels' => $travel, 'scan' => $scan, 'msg' => $msg, 'locations' => $locations]);
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

        $buscar = $request->buscar ?? 'serial';

        if ($buscar == 'serial') {
            $serial = $request->serial ?? '0';
            $regin = input::with('item', 'location', 'container')->where('serial', $serial)->orderby('id', 'desc')->simplePaginate(10);
        } else {
            $serial = $request->serial ?? '0';

            $item = item::whereRaw("item_number like '" .  $serial . "%'")->first();
            $num = $item->id ?? '0';
            $regin = input::orderby('serial')->with('item', 'location', 'container')->where('item_id', $num)->orderby('id', 'desc')->simplePaginate(10);
        }
        // $regin = input::with('item', 'location', 'container')->where('serial', $serial)->orderby('id', 'desc')->simplePaginate(10);
        // $regout = output::with('item', 'location')->where('serial', $serial)->orderby('id', 'desc')->simplePaginate(10);
        return view('Output.search', ['in' => $regin]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function edit(Output $output)
    {
        //
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
        if ($travel->location->code == 'L61       ') {
            $operador = 'O';
            $loc_ant = 'L60       ';
        } else {

            $operador = 'I';
            $loc_ant = 'L61       ';
        }
        $scan  = input::with('item')->where('travel_id', $request->travel_id)->get();
        $loc_ant_id = location::with('warehouse')->where('code', $loc_ant)->first();
        $loc_act_id = location::with('warehouse')->where('id', $travel->location->id)->first();
        foreach ($scan as $scans) {
            self::inventario($scans->serial, $scans->item_id, $scans->item->item_number, $scans->location_id, $operador, $scans->item_quantity, $loc_ant_id->id, $scans->created_at, $loc_ant_id->warehouse->code, $loc_act_id->warehouse->code);
        }
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


    public function inventario($serial, $item, $number_item, $loc, $op, $cantidad, $loc_ant, $fechahora, $WH, $wh_act)
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
        $scan  = input::with('item')->where('travel_id', $request->travel_id)->simplePaginate(10);
        $entrega = travel::with('location', 'orderinformation')->find($request->travel_id);
        $error = 1;
        $message = 'serial eliminado del viaje actual ';
        return view('output.index', ['travels' => $entrega, 'scan' => $scan, 'error' => $error, 'msg' => $message,]);
    }
}
