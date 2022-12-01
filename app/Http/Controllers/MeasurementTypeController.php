<?php

namespace App\Http\Controllers;

use App\Models\MeasurementType;
use Illuminate\Http\Request;

class MeasurementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $measurementTypes = MeasurementType::orderBy('code', 'ASC')->paginate(10);

        return view('measurement-type.index', ['measurements' => $measurementTypes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('measurement-type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MeasurementType  $measurementType
     * @return \Illuminate\Http\Response
     */
    public function show(MeasurementType $measurementType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MeasurementType  $measurementType
     * @return \Illuminate\Http\Response
     */
    public function edit(MeasurementType $measurementType)
    {
        return view('measurement-type.edit', ['measurement' => $measurementType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MeasurementType  $measurementType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MeasurementType $measurementType)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MeasurementType  $measurementType
     * @return \Illuminate\Http\Response
     */
    public function destroy(MeasurementType $measurementType)
    {
        $measurementType->delete();
        return redirect()->back();
    }
}
