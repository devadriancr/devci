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
            ->orderBy('IMENDT', 'ASC')
            ->get();

        echo count($items) . "<br>";

        foreach ($items as $key => $value) {
            // echo "$key &nbsp &nbsp $value->IID &nbsp &nbsp $value->IPROD &nbsp &nbsp $value->IDESC &nbsp &nbsp $value->IOPB &nbsp &nbsp $value->IMIN &nbsp &nbsp $value->IITYP &nbsp &nbsp $value->ICLAS &nbsp &nbsp $value->IUMS <br>";
            Item::updateOrCreate(
                [
                    'item_number' => $value->IPROD,
                ],
                [
                    'iid' => $value->IID,
                    'item_description' => preg_replace('([^A-Za-z0-9])', '', $value->IDESC),
                    //         'item_type_id' => $value->IITYP,
                    //         'item_class_id' => $value->ICLAS,
                    //         'measurement_type_id' => $value->IUMS,

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
        $items = Item::orderBy('item_number', 'ASC')->paginate(10);

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
