<?php

namespace App\Http\Controllers;

use App\Exports\ConsignmentInstructionExport;
use App\Imports\ShippingInstructionImport;
use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\ShippingInstruction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ShippingInstructionController extends Controller
{
    public function reportShipping()
    {
        $containers = Container::orderByRaw('arrival_date DESC, arrival_time DESC')->paginate(10);

        return view('shipping-instruction.report', ['containers' => $containers]);
    }

    public function downloadShipping(Request $request)
    {
        $container = Container::find($request->id);

        $consignments = ConsignmentInstruction::query()
            ->join('containers', 'consignment_instructions.container_id', '=', 'containers.id')
            ->where([
                ['containers.code', '=', $container->code],
                ['containers.arrival_date', '=', $container->arrival_date],
                ['containers.arrival_time', '=', $container->arrival_time],
            ])
            ->orderBy('supplier', 'ASC')
            ->orderBy('serial', 'ASC')
            ->get();

        $array_consignment = [];
        foreach ($consignments as $key => $consignment) {
            $serial = $consignment->supplier . $consignment->serial;
            array_push($array_consignment, [
                'container' => $consignment->container->code,
                // 'invoice' => $consignment->invoice,
                'serial' => $serial,
                'part_no' => $consignment->part_no,
                'part_qty' => $consignment->part_qty,
                'arrival_date' => $consignment->arrival_date,
                'arrival_time' => $consignment->arrival_time,
            ]);
        }

        return Excel::download(new ConsignmentInstructionExport($array_consignment), 'Scanned.xlsx');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippings = ShippingInstruction::where('status', '=', 1)->orderBy('id', 'DESC')->paginate(10);

        return view('shipping-instruction.index', ['shippings' => $shippings]);
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
        // $request->validate([
        //     'import_file' => 'required'
        // ]);

        $file = $request->file('import_file');

        Excel::import(new ShippingInstructionImport, $file);

        $containers = ShippingInstruction::query()
            ->select('container', 'arrival_date', 'arrival_time')
            ->where('status', true)
            ->distinct()
            ->get();

        foreach ($containers as $key => $container) {
            Container::storeContainer($container->container, $container->arrival_date, $container->arrival_time);
        }

        return redirect()->back()->with('success', 'Documento Importado Exitosamente');
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
        $container = Container::find($id);

        if ($container->status === true) {
            $shipping = ShippingInstruction::where('container', $container->code)->delete();
            $container->delete();
        }
        $containers = Container::orderByRaw('arrival_date DESC, arrival_time DESC')->paginate(10);

        return view('shipping-instruction.report', ['containers' => $containers]);
    }
}
