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
        $$inventories = ILI::query()
            ->select(['LPROD', 'LWHS', 'LLOC', 'LOPB'])
            ->orderBy('LOPB', 'ASC')
            ->get();

        foreach ($inventories as $key => $inventory) {
            $item = Item::where('item_number', $inventory->LPROD)->first();
            $location = Location::where('code', $inventory->LLOC)->first();

            if ($item != null && $location != null) {

                $data = Inventory::where([['item_id', $item->id], ['location_id', $location->id]])->first();

                if ($data !== null) {
                    if ($inventory->LOPB > $data->opening_balance) {
                        $transaction = TransactionType::where('code', 'LIKE', 'O ')->first();
                        $result = $inventory->LOPB - $data->opening_balance;

                        // echo "- $key Num: $inventory->LPROD *** Infor: $inventory->LOPB *** SQL:  $data->opening_balance *** Res: $result <br>";

                        Input::create(
                            [
                                'item_id' => $item->id,
                                'item_quantity' => $result,
                                'transaction_type_id' => $transaction->id,
                            ]
                        );

                        $data->update(
                            [
                                'opening_balance' => $inventory->LOPB,
                                'quantity' => 0,
                            ]
                        );
                    } elseif ($data->opening_balance > $inventory->LOPB) {
                        $transaction = TransactionType::where('code', 'LIKE', 'O ')->first();
                        $result = $data->opening_balance - $inventory->LOPB;

                        // echo "+ $key Num: $inventory->LPROD *** Infor: $inventory->LOPB *** SQL:  $data->opening_balance *** Res: $result <br>";

                        output::create(
                            [
                                'item_id' => $item->id,
                                'item_quantity' => $result,
                                'transaction_type_id' => $transaction->id,
                            ]
                        );

                        $data->update(
                            [
                                'opening_balance' => $inventory->LOPB,
                                'quantity' => 0,
                            ]
                        );
                    }
                } else {
                    $data = Inventory::create(
                        [
                            'item_id' => $item->id,
                            'location_id' => $location->id,
                            'opening_balance' => $inventory->LOPB,
                            'quantity' => 0,
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
