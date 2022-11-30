<?php

namespace App\Http\Controllers;

use App\Models\ILM;
use App\Models\Location;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function upload()
    {
        $locations = ILM::query()
            ->select('WID', 'WWHS', 'WLOC', 'WDESC')
            ->orderBy('WLOC', 'ASC')
            ->get();

        foreach ($locations as $key => $location) {
            $warehouse = Warehouse::where('code', '=', $location->WWHS)->first();

            Location::updateOrCreate(
                [
                    'code' => $location->WLOC,
                ],
                [
                    'warehouse_id' => $warehouse->id,
                    'wid' => $location->WID,
                    'name' => $location->WDESC,
                ],
            );
        }

        return redirect('location');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::orderBy('code', 'ASC')->paginate(10);

        return view('location.index', ['locations' => $locations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $warehouses = Warehouse::orderBy('code', 'ASC')->get();

        return view('location.create', ['warehouses' => $warehouses]);
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
            'code' => ['required', 'string', 'unique:locations'],
            'name' => ['required', 'string'],
            'description' => ['string', 'nullable'],
            'warehouse_id' => ['required', 'numeric'],
        ]);

        Location::create([
            'wid' => 'WL',
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'warehouse_id' => $request->warehouse_id,
        ]);

        return redirect('location');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        $warehouses = Warehouse::orderBy('code', 'ASC')->get();

        return view('location.edit', ['location' => $location, 'warehouses' => $warehouses]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'code' => ['string', 'unique:locations'],
            'name' => ['string'],
            'description' => ['string', 'nullable'],
            'warehouse_id' => ['numeric'],
        ]);

        $location->fill($request->all());

        if ($location->isDirty()) {
            $location->save();
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->back();
    }
}
