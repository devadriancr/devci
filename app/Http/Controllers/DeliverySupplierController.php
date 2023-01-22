<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\DeliveryProduction;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Output;
use App\Models\HPO;
use App\Models\OutputSupplier;
use App\Models\RYT1;
use App\Models\item;
use App\Models\input;
use App\Models\YI007;
use Illuminate\Support\Facades\DB;
use App\Models\transactiontype;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class DeliverySupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $travels = DeliveryProduction::where('deliverysupplier', '=', 1)->orderby('id', 'desc')->paginate(10);
        return view('delivery_supplier.index', ['travels' => $travels]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $location = location::where('code', 'like', '%L80%')->first();
        $use = Auth::user()->id;
        $No = DeliveryProduction::create([
            'control_number' => $request->number_control,
            'location_id' => $location->id,
            'finish' => 0,
            'deliverysupplier' => 1,
            'user_id' => $use
        ]);
        $scan =  input::where('delivery_production_id', $No->id)->simplePaginate(10);
        return view('delivery_supplier.scan', ['entrega' => $No, 'scan' => $scan]);
    }
    public function scanqr(Request $request)
    {

        $location = location::find($request->location_id);
        $scan  = OutputSupplier::with('item')->where('delivery_production_id', $request->Delivery_id)->get();
        $entrega = DeliveryProduction::find($request->Delivery_id);
        return view('delivery_supplier.scan', ['entrega' => $entrega, 'scan' => $scan, 'location_id' => $location->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $scan  = OutputSupplier::with('item')->where('delivery_production_id', $request->Delivery_id)->get();
        $loc_ant_id = location::with('warehouse')->whereRaw("code like 'L80%'")->first();
        $loc_act_id = location::with('warehouse')->whereRaw("code like 'L12%'")->first();

        foreach ($scan as $scans) {
            $serial = $scans->order_number . $scans->sequence;
            self::inventario($serial, $scans->item_id, $scans->item->item_number,$loc_act_id->id, $scans->quantity, $loc_ant_id->id, $scans->created_at, $loc_ant_id->warehouse->code, $loc_act_id->warehouse->code);
        }
        DeliveryProduction::where('id', $request->Delivery_id)
        ->update(['finish' => 1]);
            return redirect()->action([DeliverySupplierController::class, 'index']);
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

        $infor = YI007::insert(
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
        $infor = YI007::query()->insert(
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
    public function update(Request $request)
    {

        $error = 0;
        $location = location::find($request->location_id);
        $cadena = $request->serial;
        if (strlen($cadena) != 25) {
            $error = 1;
            $message = 'ESCANEO INCORRECTO';
        } else {
            $number_order = substr($cadena, 1, -12);
            $secuencia = substr($cadena, 13, -6);
            $snp = substr($cadena, -6);
        }
        if ($error == 0) {
            $order = RYT1::query()
                ->select('R1ORN', 'R1SQN', 'R1SNP', 'R1PRO')
                ->where('R1ORN', '=', $number_order)
                ->count();
            if ($order == 0) {
                $error = 2;
                $message = 'Numero de Orden no existe';
            }
        }



        if ($error == 0) {
            $serial_exist = OutputSupplier::where('Identificationcard', $cadena)->count();
            if ($serial_exist > 0) {
                $error = 12;
                $message = 'Numero de orden ya fue entregado';
            }
        }

        if ($error == 0) {
            $serial_exist = OutputSupplier::where([['Identificationcard', $cadena], ['delivery_production_id', $request->delivery_id]])->count();
            if ($serial_exist > 0) {
                $error = 3;
                $message = 'serial ya fue escaneado en esta entrega ';
            }
        }

        if ($error == 0) {
            $order_HPO = substr($number_order, 0, -4);
            $order_line = substr($number_order, -4);
            $location_old = location::where('code', 'like', 'L80%')->first();

            $Transaction_type = transactiontype::where('code', 'like', '%T %')->first();

            $result_HPO = HPO::query()
                ->select('PORD', 'PVEND', 'PPROD', 'PLINE')->where([['PORD', $order_HPO], ['PLINE', $order_line]])->first();
            $item = item::whereRaw("item_number like  '" .  $result_HPO->PPROD . "%'")->first();

            $re = OutputSupplier::create([
                'supplier' =>  $result_HPO->PVEND,
                'Identificationcard' => $cadena,
                'order_number' => $number_order,
                'sequence' => $secuencia,
                'item_id' => $item->id,
                'quantity' =>  $snp,
                'transaction_type_id' => $Transaction_type->id,
                'delivery_production_id' => $request->delivery_id,
                'location_id' => $location_old->id,
                'user_id' =>     $use = Auth::user()->id
            ]);
            $message = 'Serial capturado exitosamente';
        }

        $scan  = OutputSupplier::with('item')->where('delivery_production_id', $request->delivery_id)->get();
        $entrega = DeliveryProduction::find($request->delivery_id);
        return view('delivery_supplier.scan', ['entrega' => $entrega, 'scan' => $scan, 'error' => $error, 'msg' => $message, 'location_id' => $location->id]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $itemin = OutputSupplier::find($request->serial_id);
        $itemin->delete();
        $itemout = output::where([['delivery_production_id', $request->delivery_id], ['serial', $request->serial]])->delete();
        $scan  = OutputSupplier::with('item')->where('delivery_production_id', $request->delivery_id)->simplePaginate(10);
        $entrega = DeliveryProduction::find($request->delivery_id);
        $error = 1;
        $message = 'serial eliminado de la entrega actual ';
        return view('delivery_supplier.scan', ['entrega' => $entrega, 'scan' => $scan, 'error' => $error, 'msg' => $message,]);
    }
}
