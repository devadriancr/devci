<?php

namespace App\Http\Controllers;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\Input;
use App\Models\Item;
use App\Models\Location;
use App\Models\ShippingInstruction;
use App\Models\TransactionType;
use App\Models\YF006;
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

    public function consignment_check(Request $request)
    {
        $id = $request->container_id;
        $container = $request->container_code;
        $date = $request->container_date;
        $time = $request->container_time;

        $consignments = ConsignmentInstruction::query()
            ->join('containers', 'consignment_instructions.container_id', '=', 'containers.id')
            ->where([
                ['containers.code', '=', $container],
                ['containers.arrival_date', '=', $date],
                ['containers.arrival_time', '=', $time],
                ['containers.status', '=', true]
            ])
            ->get();

        $shipments = ShippingInstruction::query()
            ->where([
                ['container', '=', $container],
                ['arrival_date', '=', $date],
                ['arrival_time', '=', $time]
            ])
            ->orderBy('serial', 'ASC')
            ->get();

        $array_consignment = [];
        foreach ($consignments as $key => $consignment) {
            $serial = $consignment->supplier . $consignment->serial;
            array_push($array_consignment, $serial);
        }

        $arrayFound = [];
        $arrayNotFound = [];
        foreach ($shipments as $key => $shipping) {
            if (self::search($array_consignment, $shipping->serial) == false) {
                //     array_push($arrayFound, [
                //         'container' => $shipping->container,
                //         'invoice' => $shipping->invoice,
                //         'serial' => $shipping->serial,
                //         'part_no' => $shipping->part_no,
                //         'part_qty' => $shipping->part_qty,
                //         'arrival_date' => $shipping->arrival_date,
                //         'arrival_time' => $shipping->arrival_time,
                //     ]);
                // } else {
                array_push($arrayNotFound, [
                    'container' => $shipping->container,
                    'invoice' => $shipping->invoice,
                    'serial' => $shipping->serial,
                    'part_no' => $shipping->part_no,
                    'part_qty' => $shipping->part_qty,
                    'arrival_date' => $shipping->arrival_date,
                    'arrival_time' => $shipping->arrival_time,
                ]);
            }
        }

        return view('consignment-instruction.check', [
            'dataArray' => $arrayNotFound,
            'found' => count($arrayNotFound),
            'container_id' => $id,
            'container_code' => $container,
            'container_date' => $date,
            'container_time' => $time
        ]);
    }

    public function search(array $arr, $x)
    {
        if (count($arr) === 0) return false;

        $low = 0;
        $high = count($arr) - 1;

        while ($low <= $high) {
            $mid = floor(($low + $high) / 2);

            if ($arr[$mid] == $x) {
                return true;
            }

            if ($x < $arr[$mid]) {
                $high = $mid - 1;
            } else {
                $low = $mid + 1;
            }
        }

        return false;
    }

    public function consignment_finish(Request $request)
    {
        $id = $request->container_id;
        $container = $request->container_code;
        $date = $request->container_date;
        $time = $request->container_time;

        $consignments = ConsignmentInstruction::query()
            ->join('containers', 'consignment_instructions.container_id', '=', 'containers.id')
            ->where([
                ['containers.code', '=', $container],
                ['containers.arrival_date', '=', $date],
                ['containers.arrival_time', '=', $time],
                ['containers.status', '=', true]
            ])
            ->get();

        foreach ($consignments as $key => $consignment) {
            $item = Item::where('item_number', 'LIKE', '%' . $consignment->part_no . '%')->firstOrFail();
            $transaccion = TransactionType::where('code', '=', 'U3')->firstOrFail();
            $location = Location::where('code', 'LIKE', '%L60%')->firstOrFail();

            Input::storeInputConsignment(
                $consignment->supplier,
                $consignment->serial,
                $item->id,
                $consignment->part_qty,
                $consignment->container_id,
                $transaccion->id,
                $location->id,
            );
        }

        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU02.YPU180C";
        $result = odbc_exec($conn, $query);

        return redirect('consignment-instruction');
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
