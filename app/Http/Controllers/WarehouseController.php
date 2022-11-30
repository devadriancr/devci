<?php

namespace App\Http\Controllers;

use App\Models\IWM;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function upload()
    {
        $warehouses = IWM::query()
            ->select('LID', 'LWHS', 'LDESC')
            ->orderBy('LWHS', 'ASC')
            ->get();

        foreach ($warehouses as $key => $value) {
            Warehouse::updateOrCreate(
                [
                    'code' => $value->LWHS,
                ],
                [
                    'lid' => $value->LID,
                    'name' => $value->LDESC,
                ],
            );
        }

        return redirect('warehouse');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses = Warehouse::orderBy('code', 'ASC')->paginate(10);

        return view('warehouse.index', ['warehouses' => $warehouses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('warehouse.create');
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
            'code' => ['required', 'string', 'max:3', 'unique:warehouses,code'],
            'name' => ['required', 'string'],
            'description' => ['string', 'nullable'],
        ]);

        Warehouse::create([
            'lid' => 'WM',
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect('warehouse');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        return view('warehouse.edit', ['warehouse' => $warehouse]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => ['string'],
            'description' => ['string', 'nullable'],
        ]);

        $warehouse->fill($request->all());

        if ($warehouse->isDirty()) {
            $warehouse->save();
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->back();
    }
}
