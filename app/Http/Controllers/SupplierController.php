<?php

namespace App\Http\Controllers;


use App\Models\InputSupplier;

use App\Models\OutputSupplier;


use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // SupplierOrderMigrationJob::dispatch();
        // return response("Fin");

        // $transaction = TransactionType::where('code', 'LIKE', 'U%')->first();
        // $location = Location::where('code', 'LIKE', 'L80%')->first();

        // $orders = RYT1::select('R1ORN', 'R1SQN', 'R1SNP', 'R1DAT', 'R1TIM', 'R1USR')
        //     ->orderByRaw('R1DAT DESC, R1ORN DESC, R1SQN ASC')
        //     ->distinct('R1ORN')
        //     ->limit(100)
        //     ->get();

        // foreach ($orders as $key => $order) {
        //     $po = Input::where('purchase_order', $order->R1ORN)->first();

        //     if ($po === null) {
        //         $ord = floatval(substr($order->R1ORN, 0, 8));
        //         $line = floatval(substr($order->R1ORN, -4, 4));

        //         $hpo = HPO::where(
        //             [
        //                 ['PORD', $ord],
        //                 ['PLINE', $line],
        //                 ['PQREC', '>', 0]
        //             ]
        //         )->first();

        //         if ($hpo !== null) {
        //             $item = Item::where('item_number', $hpo->PPROD)->first();

        //             $inventoryItem = Inventory::where([
        //                 ['item_id', '=', $item->id],
        //                 ['location_id', '=', $location->id]
        //             ])->first();

        //             $inventoryQuantity = $inventoryItem->quantity ?? 0;
        //             $sum = $inventoryQuantity + $hpo->PQREC;

        //             Input::create(
        //                 [
        //                     'item_id' => $item->id,
        //                     'item_quantity' => $hpo->PQREC,
        //                     'transaction_type_id' => $transaction->id,
        //                     'location_id' => $location->id,
        //                     'purchase_order' => $order->R1ORN,
        //                     'user_id' => Auth::id()
        //                 ]
        //             );

        //             Inventory::updateOrCreate(
        //                 [
        //                     'item_id' =>  $item->id,
        //                     'location_id' => $location->id,
        //                 ],
        //                 [
        //                     'quantity' => $sum
        //                 ]
        //             );
        //         }
        //     }
        // }

        return redirect('input');
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

    public function indexSupplierInput(Request $request)
    {
        $search = strtoupper($request->search) ?? '';

        $suppliers = InputSupplier::join('items', 'input_suppliers.item_id', '=', 'items.id')
            ->where('input_suppliers.order_no', 'LIKE', '%' . $search . '%')
            ->orWhere('items.item_number', 'LIKE', '%' . $search . '%')
            ->orderBy('input_suppliers.received_date', 'DESC')
            ->orderBy('input_suppliers.received_time', 'DESC')
            ->paginate(10);

        return view('supplier.input', ['suppliers' => $suppliers]);
    }

    public function indexSupplierOutput(Request $request)
    {
        $search = strtoupper($request->search) ?? '';

        $suppliers = OutputSupplier::join('items', 'output_suppliers.item_id', '=', 'items.id')
            ->where('output_suppliers.order_number', 'LIKE', '%' . $search . '%')
            ->orWhere('items.item_number', 'LIKE', '%' . $search . '%')
            ->orderBy('output_suppliers.updated_at', 'DESC')
            ->paginate(10);

        return view('supplier.output', ['suppliers' => $suppliers]);
    }
}
