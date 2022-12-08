<?php

namespace App\Http\Controllers;

use App\Models\IIM;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function upload()
    {
        $opening_balances = IIM::query()
            ->select(['IPROD', 'IDESC', 'IOPB', 'ILOC', 'IWHS'])
            ->where([
                ['ICLAS', '=', 'S1'],
                ['IID', '=', 'IM']
            ])
            ->orderBy('IPROD', 'DESC')
            ->get();

        foreach ($opening_balances as $key => $opening_balance) {
            $warehouse = Warehouse::query()->where('code', '=', $opening_balance->IWHS);
            $location = Location::query()->where('code', '=', $opening_balance->ILOC);

            echo "$key &nbsp &nbsp - &nbsp &nbsp $opening_balance->IPROD &nbsp &nbsp - &nbsp &nbsp $opening_balance->IDESC
            &nbsp &nbsp - &nbsp &nbsp $opening_balance->IOPB &nbsp &nbsp - &nbsp &nbsp $opening_balance->ILOC &nbsp &nbsp - &nbsp &nbsp $opening_balance->IWHS </br>";
        }

        dd("Fin");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventories = Inventory::orderBy('item_id', 'ASC')->paginate(10);

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
