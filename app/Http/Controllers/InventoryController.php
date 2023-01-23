<?php

namespace App\Http\Controllers;

use App\Models\ILI;
use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ItemClass;
use App\Models\Location;
use App\Models\output;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    /**
     *
     */
    public function upload()
    {
        $ili = ILI::select(
            [
                'LID', 'LPROD', 'LWHS', 'LLOC', 'LOPB'
            ]
        )
            ->where('LID', 'LI')
            ->orderBy('LOPB', 'DESC')
            ->get();

        // foreach ($ili as $key => $iliVaue) {
        //     $item = Item::where('item_number', $iliVaue->LPROD)->first();
        //     $location = Location::where('code', $iliVaue->LLOC)->first();
        //     if ($item !== null && $location !== null) {

        //         $inventoryItem = Inventory::where([
        //             ['item_id', $item->id],
        //             ['location_id', $location->id]
        //         ])
        //             ->first();

        //         if ($inventoryItem === null) {
        //             Inventory::storeInventory($item->id, 0, $location->id, 0);
        //         }
        //     }
        // }

        foreach ($ili as $key => $iliVaue) {
            $item = Item::where('item_number', $iliVaue->LPROD)->first();
            $location = Location::where('code', $iliVaue->LLOC)->first();

            if ($item !== null && $location !== null) {
                $transaction = TransactionType::where('code', 'LIKE', '%O%')->first();

                $data = Inventory::where(
                    [
                        ['item_id', $item->id],
                        ['location_id', $location->id]
                    ]
                )->first();

                if ($data !== null) {
                    Input::storeOpeningBalance($item->id,  $iliVaue->LOPB, $transaction->id, $location->id);

                    $data->update(['opening_balance' => $iliVaue->LOPB]);
                } else {
                    $input = Input::storeOpeningBalance($item->id, $iliVaue->LOPB, $transaction->id, $location->id);

                    $data = Inventory::create(
                        [
                            'item_id' => $item->id,
                            'location_id' => $location->id,
                            'opening_balance' => $iliVaue->LOPB,
                        ]
                    );
                }
            } else {
                Log::info("Item: " . $iliVaue->LPROD . "Locación: " . $iliVaue->LLOC);
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
