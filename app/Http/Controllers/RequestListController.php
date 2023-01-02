<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\order;
use App\Models\location;
use App\Models\input;
use App\Models\OrderInformation;
use Illuminate\Support\Facades\Auth;
use App\Exports\OrderDetailExport;
use Maatwebsite\Excel\Facades\Excel;

class RequestListController extends Controller
{
    public function index()
    {
        $info = array();
        $reg = array();
        $loc=location::where('code','like','%L60%')->first();
        $loc_ext=location::where('code','like','%L61%')->first();
        $repor = inventory::with('item')->where('location_id', $loc->id)->get();
        foreach ($repor as $reports) {
            $snp=input::where([
            ['delivery_production_id',null],['item_id', $reports->item_id],['serial','!=',null]])->where('location_id','=',  $loc->id)->first();

            $total_boxes= ($reports->quantity+$reports->opening_balance)/$snp->item_quantity;

            $reportext = inventory::with('item')->where([['location_id', $loc_ext->id], ['item_id', $reports->item_id]])->first();
            $total_boxes_ext= ($reportext->quantity+ $reportext->opening_balance)/$snp->item_quantity;
            $reg = [
                'item_id' => $reports->item->id,
                'item' => $reports->item->item_number,
                'quantity' => $reports->quantity,
                'opening' => $reports->opening_balance,
                'boxes'=> $total_boxes,
                'safety' => $reports->item->safety_stock,
                'qtyexterno' => $reportext->quantity+ $reportext->opening_balance ?? 0,
                'boxes_ext'=> $total_boxes_ext,
            ];
            $info += [$reports->item->item_number => $reg];
        }
        return view('Requestlist.index', ['report' =>  $info]);
    }


    public function create_order(Request $request)
    {

        $numerOrder = OrderInformation::create(
            ['user_id' => Auth::user()->id, 'order_type' => 'O']
        );

        order::where('orden_information_id', null)->update(['orden_information_id' => $numerOrder->id]);
        return redirect()->action([RequestListController::class, 'list_order']);
    }



    public function list_order(Request $request)
    {
        $order = orderinformation::with('user', 'travel')->paginate(10);

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
    public function order_detail(Request $request)
    {
        $order = order::with('item')->where('orden_information_id', $request->order_id)->paginate(10);
        return view('Requestlist.order', ['order' => $order]);
    }


    public function Quitorder(Request $request)
    {
        order::find($request->item_id)->delete();
        return redirect()->route('RequestList.list_order');
    }
    public function Quitorderinformation(Request $request)
    {

        orderinformation::find($request->order_id)->delete();
       return redirect()->action([RequestListController::class, 'list_order']);
    }



    public function export(Request $request)
    {
        return Excel::download(new OrderDetailExport($request->order_id), 'report' . $request->order_id . '.xlsx');
    }

    public function send()
    {

        $numerOrder = OrderInformation::create(
            ['user_id' => Auth::user()->id, 'order_type' => 'O']
        );
        $info = array();
        $reg = array();
        $loc=location::where('code','like','%L60%')->first();
        $loc_ext=location::where('code','like','%L61%')->first();
        $repor = inventory::with('item')->where('location_id', $loc->id)->get();

        foreach ($repor as $repors) {
            $suma = ($repors->quantity + $repors->opening_balance) - $repors->item->safety_stock;
            if ($suma > 0) {
                order::updateorcreate(['item_id' => $repors->item_id], ['item_quantity' => $suma, 'orden_information_id' => $numerOrder->id]);
            }
        }
        $cant = order::where('orden_information_id', null)->count();

        return redirect()->action([RequestListController::class, 'list_order']);
    }
    public function receipt()
    {
        $numerOrder = OrderInformation::create(
            ['user_id' => Auth::user()->id, 'order_type' => 'I']
        );
        $info = array();
        $reg = array();
        $loc=location::where('code','like','%L60%')->first();
        $loc_ext=location::where('code','like','%L61%')->first();
        $repor = inventory::with('item')->where('location_id', $loc->id)->get();
        foreach ($repor as $repors) {
            $suma = ($repors->quantity + $repors->opening_balance) ;
            if ($suma < $repors->item->safety_stock) {
                $reportext = inventory::with('item')->where([['location_id', $loc_ext->id], ['item_id', $repors->item_id]])->first();
                if ($reportext != null) {
                    if ($reportext->quantity > $suma) {
                        $Qa =  $suma;
                        order::updateorcreate(['item_id' => $repors->item_id], ['item_quantity' => $Qa, 'orden_information_id' => $numerOrder->id]);
                    } else {
                        $alm_ext = $reportext->quantity ?? 0;
                        if ($alm_ext == 0) {
                        } else {
                            $Qa = $reportext->quantity;
                            order::updateorcreate(['item_id' => $repors->item_id], ['item_quantity' => $Qa, 'orden_information_id' => $numerOrder->id]);
                        }
                    }
                }
            }
        }
        return redirect()->action([RequestListController::class, 'list_order']);

    }
}
