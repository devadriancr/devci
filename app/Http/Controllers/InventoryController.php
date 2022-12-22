<?php

namespace App\Http\Controllers;

use App\Models\ILI;
use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\output;
use App\Models\TransactionType;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     *
     */
    public function upload()
    {
        $inventories = ILI::query()
            ->select(['LID', 'LPROD', 'LWHS', 'LLOC', 'LOPB'])
            ->where('LID', 'LI')
            ->orderBy('LOPB', 'DESC')
            ->get();

        foreach ($inventories as $key => $inventory) {
            $item = Item::where('item_number', $inventory->LPROD)->first();
            $location = Location::where('code', $inventory->LLOC)->first();

            if ($item != null && $location != null) {

                $data = Inventory::where([['item_id', $item->id], ['location_id', $location->id]])->first();
                $transaction = TransactionType::where('code', 'LIKE', '%O%')->first();

                if ($data !== null) {
                    $sum = $data->opening_balance + $data->quantity;

                    if ($inventory->LOPB > $sum) {
                        $result = $inventory->LOPB - $sum;

                        Input::storeInput($item->id,  $result, $transaction->id, $location->id);

                        $data->update(['opening_balance' => $inventory->LOPB, 'quantity' => 0]);
                    } elseif ($sum > $inventory->LOPB) {
                        $result = $sum - $inventory->LOPB;

                        output::storeOutput($item->id, $result, $transaction->id, $location->id);

                        $data->update(['opening_balance' => $inventory->LOPB, 'quantity' => 0]);
                    } else {
                        $result = $inventory->LOPB - $sum;

                        $data->update(['opening_balance' => $inventory->LOPB, 'quantity' => 0]);
                    }
                } else {
                    Input::storeInput($item->id, $inventory->LOPB, $transaction->id, $location->id);

                    $data = Inventory::create(
                        [
                            'item_id' => $item->id,
                            'location_id' => $location->id,
                            'opening_balance' => $inventory->LOPB,
                            'quantity' => 0
                        ]
                    );
                }
            }
        }
        return redirect('inventory');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = strtoupper($request->search) ?? '';

        $inventories = Inventory::query()
            ->join('items', 'inventories.item_id', '=', 'items.id')
            ->join('item_classes', 'items.item_class_id', '=', 'item_classes.id')
            ->join('locations', 'inventories.location_id', '=', 'locations.id')
            ->join('warehouses', 'locations.warehouse_id', '=', 'warehouses.id')
            ->where(
                [
                    ['item_classes.code', 'LIKE', '%S1%'],
                    ['items.item_number', 'LIKE', '%' . $search . '%'],
                ]
            )
            ->orWhere('locations.code', 'LIKE', '%' . $search . '%')
            ->orWhere('warehouses.code', 'LIKE', '%' . $search . '%')
            ->orderByRaw('inventories.updated_at DESC, items.item_number ASC, warehouses.code ASC, locations.code ASC')
            ->paginate(10);

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
