<?php

namespace App\Http\Controllers;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\Input;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InputController extends Controller
{
    public function consignment_container()
    {
        $containers = Container::where([
            ['status', '=', 1],
            ['arrival_date', '=', Carbon::now()]
        ])
            ->orderBy('arrival_date', 'ASC')
            ->orderBy('arrival_time', 'ASC')
            ->paginate(10);

        return view('consignment-instruction.container', ['containers' => $containers]);
    }

    public function consignment_create(Request $request)
    {
        $container = Container::findOrFail($request->container);
        $consignments = ConsignmentInstruction::where('container_id', '=', $request->container)->orderBy('created_at', 'DESC')->paginate(5);

        return view('consignment-instruction.create', ['container' => $container, 'consignments' => $consignments]);
    }

    public function consignment_store(Request $request)
    {
        $request->validate([
            'code_qr' => ['required', 'string'],
            'container_id' => ['required', 'numeric'],
        ]);

        $dataRequest = $request->code_qr;

        list($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $part_qty, $supplier, $m, $serial, $o, $p, $q, $r, $s, $t, $u, $v, $w, $x, $y, $z, $part_no) = explode(',', $dataRequest);

        ConsignmentInstruction::updateOrCreate(
            [
                'serial' => $serial,
            ],
            [
                'supplier' => $supplier,
                'part_qty' => $part_qty,
                'part_no' => $part_no,
                'container_id' => $request->container_id,
                'user_id' => Auth::id(),
            ]
        );

        return redirect()->back()->with('success', 'Registro Exitoso');
    }


    public function consignment_finish(Request $request)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
}
