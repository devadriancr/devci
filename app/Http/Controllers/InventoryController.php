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
    public function upload()
    {
        $inventories = ILI::query()
            ->select(['LPROD', 'LWHS', 'LLOC', 'LOPB'])
            ->orderBy('LPROD', 'DESC')
            ->get();

        foreach ($inventories as $key => $inventory) {
            $item = Item::where('item_number', $inventory->LPROD)->first();
            $location = Location::where('code', $inventory->LLOC)->first();
            // $data = Inventory::where([['item_id', $item->id], ['location_id', $location->id]])->first();
            // $result = 0;

            // if ($data === null) {
            //     echo "Entro";
            // }

            // if ($data !== null && $item !== null && $location !== null) {
            //     if ($inventory->LOPB > $data->opening_balance) {
            //         $transaction = TransactionType::where('code', '=', 'O ')->first();
            //         $result = $inventory->LOPB - $data->opening_balance;
            //         output::create(
            //             [
            //                 'item_id' => $item->id,
            //                 'item_quantity' => $result,
            //                 'transaction_type_id' => $transaction->id,
            //             ]
            //         );
            //     } else {
            //         $transaction = TransactionType::where('code', 'LIKE', 'O ')->get();
            //         $result = $data->opening_balance - $inventory->LOPB;
            //         Input::create(
            //             [
            //                 'item_id' => $item->id,
            //                 'item_quantity' => $result,
            //                 'transaction_type_id' => $transaction->id,
            //             ]
            //         );
            //     }
            // }

            Inventory::updateOrCreate(
                [
                    'item_id' => $item->id ?? null,
                    'location_id' => $location->id ?? null,
                ],
                [
                    'opening_balance' => $inventory->LOPB,
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
        $inventories = Inventory::query()
            ->join('items', 'inventories.item_id', '=', 'items.id')
            ->join('item_classes', 'items.item_class_id', '=', 'item_classes.id')
            ->where('item_classes.code', 'LIKE', '%S1%')
            ->orderBy('items.item_number', 'DESC')
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
