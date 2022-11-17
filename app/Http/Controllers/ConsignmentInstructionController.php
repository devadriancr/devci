<?php

namespace App\Http\Controllers;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\Item;
use App\Models\ShippingInstruction;
use App\Models\TransactionType;
use App\Models\YF006;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsignmentInstructionController extends Controller
{
    public function container()
    {
        $containers = Container::where([['date', '=', Carbon::now()->format('Ymd')], ['status', '=', 1]])->orderBy('date', 'ASC')->orderBy('time', 'ASC')->get();

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

        list($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $part_qty, $supplier, $m, $serial, $o, $p, $q, $r, $s, $t, $u, $v, $w, $x, $y, $z, $part_no) = explode(',', $dataRequest);

        $consignment = ConsignmentInstruction::create([
            'supplier' => $supplier,
            'serial' => $serial,
            'part_qty' => $part_qty,
            'part_no' => $part_no,
            'container_id' => $request->container_id,
            'user_id' => Auth::id(),
        ]);

        $transaction = TransactionType::where('code', '=', 'I')->first();

        $consignment->transactions()->sync($transaction->id);

        return redirect()->back()->with('success', 'Registro Exitoso');
    }

    public function check(Request $request)
    {
        $container = $request->container_code;
        $date = $request->container_date;
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
            ->orderBy('serial', 'ASC')
            ->get();

        $arrayFound = [];

        foreach ($shipments as $key => $shipment) {
            foreach ($consignments as $key => $consignment) {
                $serialConsignment = $consignment->supplier . $consignment->serial;
                if ($shipment->serial == $serialConsignment) {
                    array_push($arrayFound, [
                        'container' => $shipment->container,
                        'invoice' => $shipment->invoice,
                        'supplier' => $consignment->supplier,
                        'serial' => $consignment->serial,
                        'part_no' => $shipment->part_no,
                        'part_qty' => $shipment->part_qty,
                        'date' => $shipment->date,
                        'time' => $shipment->time,
                    ]);
                }
            }
        }

        return view('consignment-instruction.index', ['dataArray' => $arrayFound, 'found' => count($arrayFound), 'total' => count($shipments), 'container_id' => $request->container_id]);
    }

    public function finish(Request $request)
    {
        foreach ($request->arrayData as $key => $data) {
            $consignment = ConsignmentInstruction::query()->where('serial', '=', $data['serial'])->first();
            $item = Item::query()->where('item', 'LIKE', '%' . $consignment->part_no . '%')->first();

            // SQL
            $consignment->items()->sync([$item->id]);

            dd($consignment);

            // INFOR
            $insert = YF006::query()->insert([
                'H3CONO' => $data['container'],
                'H3DDTE' => $data['date'],
                'H3DTIM' => Carbon::parse($data['time'])->format('Hi'),
                'H3PROD' => $data['part_no'],
                'H3SUCD' => $data['supplier'],
                'H3SENO' => $data['serial'],
                'H3RQTY' => $data['part_qty'],
                'H3RDTE' => Carbon::parse($consignment->created_at)->format('Ymd'),
                'H3RTIM' => Carbon::parse($consignment->created_at)->format('His'),
            ]);
        }

        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU02.YPU180C";
        $result = odbc_exec($conn, $query);

        $container = Container::where([['id', '=', $request->container_id]])->update(['status' => 0]);

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
