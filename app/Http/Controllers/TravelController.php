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
       $ordernumber= $request->order_id ?? 0;
        $travels = Travel::with('location','orderinformation')->orderby('id', 'desc')
            ->simplePaginate(10);
        $locations = location::where('code', 'like', '%L60%')->orwhere('code', 'like', '%L61%')->get();
        return view('travel.index', ['travels' => $travels, 'locations' => $locations,'order_number'=>$ordernumber]);
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
            'carta_porte' => ['required', 'string', 'max:30', 'min:5', 'unique:travel'],
            'invoice_number' => ['required', 'string', 'max:20', 'min:5', 'unique:travel'],
            'location_id' => ['required', 'string', 'max:20'],
            'order_id' => ['required', 'int'],
        ]);

        $idtravel = Travel::create(
            [
                'carta_porte' => $request->carta_porte,
                'invoice_number' => $request->invoice_number,
                'location_id' => $request->location_id,
            ]
        );

        // // $travel = DB::table('travel')->where('carta_porte', $request->carta_porte)->first();

        orderinformation::find($request->order_id)->update(['travel_id'=>$idtravel->id]);
        $msg = '';
        $scan = input::find($idtravel->id)->paginate(10);

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
        $travels = Travel::orderby('id', 'desc')
            ->paginate(10);
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
