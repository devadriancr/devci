<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\order;
use App\Models\OrderInformation;
use Illuminate\Support\Facades\Auth;
use App\Exports\OrderDetailExport ;
use Maatwebsite\Excel\Facades\Excel;

class RequestListController extends Controller
{
    public function index()
    {
        $info = array();
        $reg = array();
        $repor = inventory::with('item')->where('location_id', 328)->get();
        foreach ($repor as $reports) {
            $reportext = inventory::with('item')->where([['location_id', 329], ['item_id', $reports->item_id]])->first();
            $reg = [
                'item_id' => $reports->item->id,
                'item' => $reports->item->item_number,
                'quantity' => $reports->quantity,
                'opening' => $reports->opening_balance,
                'safety' => $reports->item->safety_stock,
                'qtyexterno' => $reportext->quantity ?? 0
            ];
            $info += [$reports->item->item_number => $reg];
        }

        return view('Requestlist.index', ['report' =>  $info]);
    }


    public function create_order(Request $request)
    {       $numerOrder = OrderInformation::create(
            ['user_id' => Auth::user()->id, 'order_type'=>'O']
        );
        order::where('orden_information_id', null)->update(['orden_information_id' => $numerOrder->id]);
        return redirect()->action([RequestListController::class, 'list_order']);
    }



    public function list_order(Request $request)
    {
        $order = orderinformation::with('user')->join('travel','travel.id','=','travel_id')->paginate(10);

        return view('Requestlist.orderinformation', ['order' => $order]);
    }
    public function order(Request $request)
    {
        order::updateorcreate(
            [
                'item_id' => $request->item_id, 'orden_information_id' => null
            ],
            ['item_id' => $request->item_id, 'item_quantity' => $request->quantity]
        );
        return redirect()->action([RequestListController::class, 'order_detail']);

    }
    public function order_detail()
    {
        $order = order::with('item')->where('orden_information_id', null)->paginate(10);
        return view('Requestlist.order', ['order' => $order]);
    }


    public function Quitorder(Request $request)
    {
        order::find($request->item_id)->delete();
        return redirect()->action([RequestListController::class, 'index']);
    }



    public function export(Request $request)
    {
        return Excel::download(new OrderDetailExport($request->order_id), 'report' . $request->order_id . '.xlsx');
    }

    public function send()
    {
        $info = array();
        $reg = array();
        $repor = inventory::with('item')->where('location_id', 328)->get();
        foreach($repor as $repors)
        {
            $suma=($repors->quantity+ $repors->opening_balance)-$repors->item->safety_stock;
            if($suma>0)
            {
                order::updateorcreate(['item_id'=> $repors->item_id,'orden_information_id'=>null],['item_quantity'=>$suma]);
            }
        }
        return redirect()->action([RequestListController::class, 'order_detail']);
    }
    public function receipt()
    {
        $info = array();
        $reg = array();
        $repor = inventory::with('item')->where('location_id', 328)->get();
        foreach($repor as $repors)
        {
            $suma=($repors->quantity+ $repors->opening_balance)-$repors->item->safety_stock;
            $reportext = inventory::with('item')->where([['location_id', 329], ['item_id', $repors->item_id]])->first();
            $alm_ext=$reportext->quantity ?? 0;
            $suma=$suma+$alm_ext;
            if($suma>0)
            {
                order::updateorcreate(['item_id'=> $repors->item_id,'order_information_id'=>null],['item_quantity'=>$suma]);
            }
        }
        return redirect()->action([RequestListController::class, 'order_detail']);
    }
}
