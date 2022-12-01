<?php

namespace App\Http\Controllers;

use App\Models\IIC;
use App\Models\ItemClass;
use Illuminate\Http\Request;

class ItemClassController extends Controller
{
    public function upload()
    {
        $classes = IIC::query()
            ->select('IID', 'ICLAS', 'ICDES')
            ->orderBy('ICLAS', 'ASC')
            ->get();

        foreach ($classes as $key => $class) {
            ItemClass::updateOrCreate(
                [
                    'code' => $class->ICLAS,
                ],
                [
                    'iid' => $class->IID,
                    'name' =>  $class->ICDES,
                ],
            );
        }

        return redirect('item-class');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itemClasses = ItemClass::orderBy('code', 'ASC')->paginate(10);

        return view('item-class.index', ['itemClasess' => $itemClasses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('item-class.create');
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

        ItemClass::create([
            'iid' => 'IC',
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect('item-class');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemClass  $itemClass
     * @return \Illuminate\Http\Response
     */
    public function show(ItemClass $itemClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemClass  $itemClass
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemClass $itemClass)
    {
        return view('item-class.edit', ['itemClass' => $itemClass]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemClass  $itemClass
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemClass $itemClass)
    {
        $request->validate([
            'code' => ['string'],
            'name' => ['string'],
        ]);

        $itemClass->fill($request->all());

        if ($itemClass->isDirty()) {
            $itemClass->save();
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemClass  $itemClass
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemClass $itemClass)
    {
        $itemClass->delete();
        return redirect()->back();
    }
}
