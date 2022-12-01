<?php

namespace App\Http\Controllers;

use App\Models\ItemType;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itemTypes = ItemType::orderBy('code', 'ASC')->paginate(10);

        return view('item-type.index', ['itemTypes' => $itemTypes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('item-type.create');
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
            'code' => ['required', 'string', 'unique:item_types'],
            'name' => ['required', 'string'],
        ]);

        ItemType::create([
            'lid' => 'WM',
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect('item-type');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemType  $itemType
     * @return \Illuminate\Http\Response
     */
    public function show(ItemType $itemType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemType  $itemType
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemType $itemType)
    {
        return view('item-type.edit', ['itemType' => $itemType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemType  $itemType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemType $itemType)
    {
        $request->validate([
            'code' => ['string', 'unique:item_types'],
            'name' => ['string'],
        ]);

        $itemType->fill($request->all());

        if ($itemType->isDirty()) {
            $itemType->save();
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemType  $itemType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemType $itemType)
    {
        $itemType->delete();
        return redirect()->back();
    }
}
