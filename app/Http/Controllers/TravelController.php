<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use App\Models\Input;
use App\Models\Location;
use App\Models\OrderInformation;
use App\Export\ShippingExport;
use App\Exports\ShippingExport as ExportsShippingExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;




class TravelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ordernumber = $request->order_id ?? 0;
        if ($ordernumber == 0) {
            $locations = location::where('code', 'like', '%L60%')->orwhere('code', 'like', '%L61%')->get();
        } else {
            $info_order = orderinformation::where('id',$ordernumber)->first();
            if ($info_order->order_type ==  "O") {
                $locations = location::where('code', 'like', '%L61%')->get();
            } else {
                $locations = location::where('code', 'like', '%L60%')->get();
            }
        }



        $travels = Travel::with('location')->orderby('id', 'desc')
            ->simplePaginate(10);



        return view('travel.index', ['travels' => $travels, 'locations' => $locations, 'order_number' => $ordernumber]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        $request->validate([
            'carta_porte' => ['required', 'string', 'max:30', 'min:5'],
            'invoice_number' => ['required', 'string', 'max:20', 'min:5'],
            'location_id' => ['required', 'string', 'max:20'],
            'order_id' => ['required', 'int'],
        ]);

        $busca = travel::where('invoice_number', $request->invoice_number)->count();
        if ($busca > 0) {
            return redirect()->route('Travel.index');
        }
        $idtravel = Travel::updateorcreate(
            [
                'carta_porte' => $request->carta_porte,
                'invoice_number' => $request->invoice_number,
                'location_id' => $request->location_id,
            ]
        );

        // // $travel = DB::table('travel')->where('carta_porte', $request->carta_porte)->first();

        orderinformation::find($request->order_id)->update(['travel_id' => $idtravel->id]);
        $msg = '';
        $scan = input::where('travel_id', $idtravel->id)->paginate(10);

        return view('output.index', ['travels' => $idtravel, 'scan' => $scan]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function show(Travel $travel)
    {
        $travels = Travel::with('location')->orderby('id', 'desc')
        ->simplePaginate(10);

    return view('travel.list_travel', ['travels' => $travels]);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function edit(Travel $travel)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Travel $travel)
    {
        $travel->Update([
            'departure_date' => $request->departure_date
        ]);
        $travels = Travel::orderby('id', 'desc')->paginate(10);
        $locations = location::get();
        return view('Travel.index', ['travels' => $travels, 'locations' => $locations]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Travel $travel)
    {
        //
    }
    public function export(Request $request)
    {
        return Excel::download(new ExportsShippingExport($request->travel_id), 'report' . $request->travel_id . '.xlsx');
    }
}
