<?php

namespace App\Http\Controllers;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\ShippingInstruction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsignmentInstructionController extends Controller
{
    public function container()
    {
        $containers = Container::where([['date', '=', Carbon::now()->format('Y-m-d')], ['status', '=', 1]])->orderBy('date', 'ASC')->orderBy('time', 'ASC')->get();

        return view('consignment-instruction.container', ['containers' => $containers]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd("¿Alto!");
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

    public function check(Request $request)
    {
        $shipments = ShippingInstruction::where([['container', '=', $request->container_code]])->get();
        $consignments = ConsignmentInstruction::join('containers', 'containers.id', '=', 'consignment_instructions.container_id')->where([['container_id', '=', $request->container_id]])->get();
        $dataArray = [];
        foreach ($shipments as $shipment) {
            foreach ($consignments as $consignment) {
                if ($shipment->container == $consignment->container->code) {
                    if ($shipment->serial == $consignment->serial) {
                        array_push($dataArray, ['container' => $shipment->container, 'invoice' => $shipment->invoice, 'serial' => $shipment->serial, 'part_no' => $shipment->part_no, 'part_qty' => $shipment->part_qty]);
                    }
                }
            }
        }
        dd($dataArray);

        return view('consignment-instruction.index', ['data' => $dataArray]);
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

        $consignment = ConsignmentInstruction::create([
            'serial' => $request->serial,
            'container_id' => $request->container_id,
            'user_id' => Auth::id(),
        ]);

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
