<?php

namespace App\Http\Controllers;

use App\Models\ILI;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function upload()
    {
        $inventories = ILI::query()
            ->select(['LPROD', 'LWHS', 'LLOC', 'LOPB'])
            ->orderBy('LPROD', 'DESC')
            ->get();

        foreach ($inventories as $key => $inventory) {
            $item = Item::where('item_number', $inventory->LPROD)->first();
            $location = Location::where('code', $inventory->LLOC)->first();

            Inventory::updateOrCreate(
                [
                    'item_id' => $item->id ?? null,
                    'location_id' => $location->id ?? null,
                ],
                [
                    'quantity' => $inventory->LOPB,
                ],
            );
        }

        return redirect('inventory');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventories = Inventory::orderBy('item_id', 'DESC')->paginate(10);

        return view('inventory.index', ['inventories' => $inventories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
