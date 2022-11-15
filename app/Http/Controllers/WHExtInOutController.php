<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\WherehouseInOut;
use App\Models\ShippingInstruction;
use App\Models\ShippingInOut;
use Illuminate\Support\Facades\Auth;
use App\Exports\ShippingExport;
use App\Exports\ShippingExportDetail;
use Maatwebsite\Excel\Facades\Excel;

class WHExtInOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id=$request->id;
        $text='';
        if($id==1)
        {
            $text='W10-L12';
        }else{
            $text='W61-L61';
        }
        $error = 0;
        $scan =  WherehouseInOut::query()->select('serial', 'part_no', 'part_qty')->where('status', '0')->get();
        return view('WhereHouse_In_Out.index', ['scan' => $scan, 'error' => $error,'id'=>$id,'texto'=>$text]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error = 0;
        $msgerror = '';
        $id=$request->id;
        $text='';
        if($id==1)
        {
            $text='W10-L12';

        }else{
            $text='W61-L61';

        }
        $QR = explode(',', $request->serial);
        $serial = $QR[11] . $QR[13];
        $buscarShip = ShippingInstruction::query()
            ->select('serial', 'part_no', 'part_qty')
            ->where('serial', '=', $serial)->count();
        if ($buscarShip == 0) {
            $error = 1;
            $msgerror = 'Serial no encontrado en Shipping Instruction';
        }

        $buscar = WherehouseInOut::query()->select('serial', 'part_no', 'part_qty')->where('serial', '=', $serial)->get()->toarray();
        $cont = count($buscar);

        if ($cont > 0) {
            $error = 2;
            $msgerror = 'Serial repetido';
        }

        if ($error == 0) {
            $infor = ShippingInstruction::query()
                ->select('serial', 'part_no', 'part_qty')
                ->where('serial', '=', $serial)->first();
            WherehouseInOut::query()->insert(
                [
                    'serial' => $infor->serial,
                    'part_no' => $infor->part_no,
                    'part_qty' => $infor->part_qty,
                    'date_Scan' => now(),
                    'time_scan' => date('H:i:s '),
                    'status' => 0,
                    'shippign' => 'No Assig '
                ]
            );
        }
        $scan =  WherehouseInOut::query()->select('serial', 'part_no', 'part_qty', 'date_Scan', 'time_scan', 'status', 'shippign')
            ->where('status', '0')->get();

        return view('WhereHouse_In_Out.index', ['scan' => $scan, 'error' => $error, 'msgerror' => $msgerror,'id'=>$id,'texto'=>$text]);
    }
    public function shipping(Request $request)
    {
        $id=$request->id;

        $text='';
        if($id==1)
        {
            $text='W10-L12';
            $flag='I';
        }else{
            $text='W61-L61';
            $flag='O';
        }

        $error = 0;
        $msgerror = '';
        $scan =  WherehouseInOut::query()->select('serial', 'part_no', 'part_qty', 'date_Scan', 'time_scan', 'status', 'shippign')
            ->where('status', '0')->count();
        if ($scan > 0) {
            ShippingInOut::query()->insert(
                [
                    'usuario' => Auth::id(),
                    'fecha_shi' => now(),
                    'hora_shi' => date('H:i:s'),
                    'transfer_flag'=> $flag,
                    'Wharehouse'=>$text
                ]

            );
            $val = ShippingInOut::query()->select('id')->orderby('id', 'desc')->first();
            WherehouseInOut::query()->where('status', '0')->update(['status' => '1', 'shippign' => $val->id]);
        } else {
            $error = 1;
            $msgerror = 'No hay escaneos pendientes';
        }
        $scan =
            $val = ShippingInOut::query()->select('shipping_in_outs.id','name','fecha_shi','hora_shi','transfer_flag','wharehouse')->join('users','users.id','=','usuario')->orderby('shipping_in_outs.id', 'desc')->get();
        return view('WhereHouse_In_Out.shipping', ['scan' => $scan, 'error' => $error, 'msgerror' => $msgerror,'id'=>$id,'texto'=>$text]);
    }

    public function export(Request $request)
    {
        $id=$request->id;
        return Excel::download(new ShippingExport($id ), 'Scan.xlsx');
    }
    public function exportDetail(Request $request)
    {
        $id=$request->id;
        return Excel::download(new ShippingExportDetail($id ), 'Scan.xlsx');
    }
    public function saveshipping(Request $request)
    {
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
