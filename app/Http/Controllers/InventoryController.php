<?php

namespace App\Http\Controllers;

use App\Imports\DuplicateEntriesImport;
use App\Imports\InventoryAmountOpeningBalanceImport;
use App\Jobs\OpeningBalanceJob;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    /**
     *
     */
    public function upload()
    {
        OpeningBalanceJob::dispatch();

        return redirect('inventory');
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'import_file' => 'required', 'mimes:xls,xlsx,csv'
        ]);

        $file = $request->file('import_file');

        Excel::import(new InventoryAmountOpeningBalanceImport, $file);

        return redirect()->back()->with('success', 'Documento Importado Exitosamente');
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

    public function duplicateEntries(Request $request)
    {
        return view('inventory.duplicate');
    }

    public function duplicateEntryFile(Request $request)
    {
        $request->validate([
            'import_file' => 'required', 'mimes:xls,xlsx,csv'
        ]);

        $file = $request->file('import_file');

        Excel::import(new DuplicateEntriesImport, $file);

        return redirect()->back()->with('success', 'Documento Importado Exitosamente');
    }
}
