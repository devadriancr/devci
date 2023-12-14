<?php

namespace App\Http\Controllers;

use App\Models\Input;
use Illuminate\Http\Request;

class InputController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = strtoupper($request->search) ?? '';

        $inputs = Input::query()
            ->select(
                'inputs.supplier',
                'inputs.serial',
                'items.item_number',
                'inputs.item_quantity',
                'transaction_types.code',
                'inputs.created_at AS date',
                'locations.code AS name_loc',
                'warehouses.code AS name_whs',
                'containers.code AS cont'
            )
            ->join('items', 'inputs.item_id', '=', 'items.id')
            ->leftJoin('transaction_types', 'inputs.transaction_type_id', '=', 'transaction_types.id')
            ->leftJoin('locations', 'locations.id', '=', 'inputs.location_id')
            ->leftJoin('warehouses', 'warehouses.id', '=', 'locations.warehouse_id')
            ->leftJoin('containers', 'containers.id', '=', 'inputs.container_id')
            ->where('inputs.serial', 'LIKE', '%' . $search . '%')
            ->orWhere('items.item_number', 'LIKE', '%' . $search . '%')
            ->orderBy('inputs.created_at', 'DESC')
            ->paginate(10);

        return view('input.index', ['inputs' => $inputs]);
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
     * @param  \App\Models\Input  $input
     * @return \Illuminate\Http\Response
     */
    public function show(Input $input)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Input  $input
     * @return \Illuminate\Http\Response
     */
    public function edit(Input $input)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Input  $input
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Input $input)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Input  $input
     * @return \Illuminate\Http\Response
     */
    public function destroy(Input $input)
    {
        //
    }

    /**
     *
     */
    function itemReport()
    {
        return view('input.item-report');
    }

    /**
     *
     */
    function downloadItemReport(Request $request)
    {
        $data = explode(',', $request->item);
        dd($data);
    }
}
