<?php

namespace App\Http\Controllers;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ConsignmentInstructionController extends Controller
{
    public function container()
    {
        $containers = Container::where('date', '=', Carbon::now()->format('Y-m-d'))->orderBy('date', 'ASC')->orderBy('time', 'ASC')->get();

        return view('consignment-instruction.container', ['containers' => $containers]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $containers = Container::where('date', '=', Carbon::now()->format('Y-m-d'))->orderBy('created_at', 'ASC')->get();
        $consignments = ConsignmentInstruction::orderBy('created_at', 'DESC')->paginate(10);

        return view('consignment-instruction.index', ['consignments' => $consignments, 'containers' => $containers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $container = Container::find($request->container);
        $consignments = ConsignmentInstruction::where('container_id', '=', $request->container)->orderBy('created_at', 'DESC')->paginate(5);

        return view('consignment-instruction.create', ['container' => $container, 'consignments' => $consignments]);
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
            'serial' => ['required', 'string', 'max:14', 'min:14', 'unique:consignment_instructions'],
        ]);

        $data = $request->only(['serial', 'container_id']);
        $consignment = ConsignmentInstruction::create($data);

        return redirect()->back()->with('success', 'Registro Exitoso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
