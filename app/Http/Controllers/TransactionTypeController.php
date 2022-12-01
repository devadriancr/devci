<?php

namespace App\Http\Controllers;

use App\Models\ITE;
use App\Models\TransactionType;
use Illuminate\Http\Request;

class TransactionTypeController extends Controller
{
    public function upload()
    {
        $transactions = ITE::query()
            ->select('TID', 'TTYPE', 'TDESC')
            ->orderBy('TTYPE', 'ASC')
            ->get();

        foreach ($transactions as $key => $transaction) {
            TransactionType::updateOrCreate(
                [
                    'code' => $transaction->TTYPE,
                ],
                [
                    'tid' => $transaction->TID,
                    'name' => $transaction->TDESC,
                ],
            );
        }
        return redirect('transaction-type');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = TransactionType::orderBy('code', 'ASC')->paginate(10);

        return view('transaction-type.index', ['transactions' => $transactions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transaction-type.create');
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
            'code' => ['required', 'string', 'unique:transaction_types'],
            'name' => ['required', 'string'],
            'description' => ['string', 'nullable'],
        ]);

        TransactionType::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect('transaction-type');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransactionType  $transactionType
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionType $transactionType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransactionType  $transactionType
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionType $transactionType)
    {
        return view('transaction-type.edit', ['transaction' => $transactionType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransactionType  $transactionType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransactionType $transactionType)
    {
        $request->validate([
            'code' => ['string'],
            'name' => ['string'],
            'description' => ['string', 'nullable'],
        ]);

        $transactionType->fill($request->all());

        if ($transactionType->isDirty()) {
            $transactionType->save();
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransactionType  $transactionType
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionType $transactionType)
    {
        $transactionType->delete();
        return redirect()->back();
    }
}
