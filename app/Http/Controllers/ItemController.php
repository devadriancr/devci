<?php

namespace App\Http\Controllers;

use App\Models\IIM;
use App\Models\Item;
use App\Models\ItemClass;
use App\Models\ItemType;
use App\Models\MeasurementType;
use App\Models\YH005;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     *
     */
    public function upload()
    {
        $items = IIM::query()
            ->select(['IID', 'IPROD', 'IDESC', 'IOPB', 'IMIN', 'IITYP', 'ICLAS', 'IUMS', 'IMENDT', 'IMENTM',])
            ->orderBy('IMENDT', 'ASC')
            ->get();

        // echo count($items) . "<br>";

        foreach ($items as $key => $value) {
            // echo "$key &nbsp &nbsp $value->IID &nbsp &nbsp $value->IPROD &nbsp &nbsp $value->IDESC &nbsp &nbsp $value->IOPB &nbsp &nbsp $value->IMIN &nbsp &nbsp $value->IITYP &nbsp &nbsp $value->ICLAS &nbsp &nbsp $value->IUMS <br>";

            $itemType = ItemType::where('code', '=', $value->IITYP)->first();
            $itemClass = ItemClass::where('code', '=', $value->ICLAS)->first();
            $measurementType = MeasurementType::where('code', '=', $value->IUMS)->first();

            Item::updateOrCreate(
                [
                    'item_number' => $value->IPROD,
                ],
                [
                    'iid' => $value->IID,
                    'item_description' => preg_replace('([^A-Za-z0-9])', '', $value->IDESC),
                    'item_type_id' => $itemType->id,
                    'item_class_id' => $itemClass->id,
                    'measurement_type_id' => $measurementType->id,
                ],
            );
        }

        return redirect('item');
    }

    /**
     *
     */
    public function safetyStock()
    {
        $items = YH005::groupBy('H5CPRO')->selectRaw('H5CPRO, SUM(H5UQTY) AS TOTAL')->get();

        foreach ($items as $key => $item) {
            $status = Item::updateOrCreate(
                ['item_number' => $item->H5CPRO],
                ['safety_stock' => $item->TOTAL]
            );
        }

        return redirect('item');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $class = ItemClass::where('code', 'LIKE', '%S1%')->first();
        $items = Item::query()
            ->where(
                [
                    ['item_class_id', $class->id],
                    ['item_number', 'LIKE', '%' . $search . '%']
                ]
            )
            ->orderBy('updated_at', 'DESC')
            ->orderBy('item_number', 'ASC')
            ->paginate(10);

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
