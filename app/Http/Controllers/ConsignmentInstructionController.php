<?php

namespace App\Http\Controllers;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\ShippingInstruction;
use Carbon\Carbon;
use Carbon\Doctrine\CarbonDoctrineType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\AssignOp\Concat;
use Symfony\Component\Mailer\Transport\Dsn;

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
        // dd("¿Alto!");
        // $containers = Container::where('date', '=', Carbon::now()->format('Y-m-d'))->orderBy('created_at', 'ASC')->get();
        // $consignments = ConsignmentInstruction::orderBy('created_at', 'DESC')->paginate(10);

        // return view('consignment-instruction.index', ['consignments' => $consignments, 'containers' => $containers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'container' => ['required', 'numeric'],
        ]);
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
            'code_qr' => ['required', 'string'],
        ]);

        $dataRequest = $request->code_qr;

        list($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $supplier, $m, $serial, $o, $p, $q, $r, $s, $t, $u, $v, $w, $part_qty, $y, $z, $part_no) = explode(',', $dataRequest);

        $consignment = ConsignmentInstruction::create([
            'supplier' => $supplier,
            'serial' => $serial,
            'part_qty' => $part_qty,
            'part_no' => $part_no,
            'container_id' => $request->container_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Registro Exitoso');
    }

    public function check(Request $request)
    {
        $container = $request->container_code;
        $date = Carbon::parse($request->container_date)->format('Ymd');
        $time = $request->container_time;

        $consignments = ConsignmentInstruction::join('containers', 'consignment_instructions.container_id', '=', 'containers.id')
            ->where([
                ['containers.date', '=', $date],
                ['containers.time', '=', $time],
                ['containers.status', '=', true]
            ])
            ->get();

        $shipments = ShippingInstruction::where([
                ['container', '=', $container],
                ['date', '=', $date],
                ['time', '=', $time]
            ])
            ->get();

        $arrayFount = [];
        $arrayNotFount = [];

        foreach ($consignments as $consignment) {
            foreach ($shipments as $shipment) {
                $serial = $consignment->supplier . $consignment->serial;
                if ($shipment->serial == $serial) {
                    array_push($arrayFount, ['container' => $shipment->container, 'invoice' => $shipment->invoice, 'serial' => $shipment->serial, 'part_no' => $shipment->part_no, 'part_qty' => $shipment->part_qty, 'date' => $shipment->date, 'time' => $shipment->time]);
                } else {
                    array_push($arrayNotFount, ['container' => $shipment->container, 'invoice' => $shipment->invoice, 'serial' => $shipment->serial, 'part_no' => $shipment->part_no, 'part_qty' => $shipment->part_qty, 'date' => $shipment->date, 'time' => $shipment->time]);
                }
            }
        }
        $details = self::unique_multidim_array($arrayNotFount, 'serial');

        return view('consignment-instruction.index', ['dataArray' => $details, 'container_id' => $request->container_id]);
    }

    public function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

    public function finish(Request $request)
    {
        $container = Container::where([
            ['id', '=', $request->container_id]
        ])
            ->update(['status' => 0]);

        return redirect('container-ci');
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
