<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $travels = Travel::with('location')->orderby('id', 'desc')
            ->simplePaginate(10);
        $locations = location::where('code','like','%L60%')->orwhere('code','like','%L61%')->get();
        return view('travel.index', ['travels' => $travels, 'locations' => $locations]);
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
        ]);

        $idtravel=Travel::create(
            [
                'carta_porte' => $request->carta_porte,
                'invoice_number' => $request->invoice_number,
                'location_id' => $request->location_id,
            ]
        );

        // // $travel = DB::table('travel')->where('carta_porte', $request->carta_porte)->first();
        $msg='';
        $scan = array();
        $travels = Travel::orderby('id', 'desc')
            ->simplePaginate(10);
        $locations = location::get();

        return view('output.index', ['travels' =>$idtravel, 'locations' => $locations,'scan'=>$scan,'msg'=>$msg]);
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
            ->simplePaginate(10);
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
}
