<?php

namespace App\Http\Controllers;

use App\Models\IIM;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function upload()
    {
        $items = IIM::query()
            ->select(['IID', 'IPROD', 'IDESC', 'IOPB', 'IMIN', 'IITYP', 'ICLAS', 'IUMS', 'IMENDT', 'IMENTM',])
            ->orderBy('IMENDT', 'DESC')
            ->get();

        // echo count($items) . "<br>";

        foreach ($items as $key => $value) {

            // echo "$key &nbsp &nbsp $value->IID &nbsp &nbsp $value->IPROD &nbsp &nbsp $value->IDESC &nbsp &nbsp $value->IOPB &nbsp &nbsp $value->IMIN &nbsp &nbsp $value->IITYP &nbsp &nbsp $value->ICLAS &nbsp &nbsp $value->IUMS <br>";

            Item::updateOrCreate(
                [
                    'item' => $value->IPROD,
                ],
                [
                    'status' => $value->IID,
                    'description' => preg_replace('([^A-Za-z0-9])', '', $value->IDESC),
                    'opening_balance' => $value->IOPB,
                    'minimum_balance' => $value->IMIN,
                    'item_type' => $value->IITYP,
                    'item_class' => $value->ICLAS,
                    'measurement_unit' => $value->IUMS,
                    'creation_date' => $value->IMENDT,
                    'creation_time' => $value->IMENTM,
                ],
            );
        }

        return redirect('item');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::where([['item_class', '=', 'S1'], ['status', '=', 'IM']])->paginate(10);

        return view('items.index', ['items' => $items]);
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
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }
}
