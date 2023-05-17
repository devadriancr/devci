<?php

namespace App\Jobs;

use App\Models\InputSupplier;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\TransactionType;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreSupplierOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private $supplier;
    private $orderNo;
    private $sequence;
    private $item;
    private $snp;
    private $receivedDate;
    private $receivedTime;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($supplier, $orderNo, $sequence, $item, $snp, $receivedDate, $receivedTime)
    {
        $this->supplier = $supplier;
        $this->orderNo = $orderNo;
        $this->sequence = $sequence;
        $this->item = $item;
        $this->snp = $snp;
        $this->receivedDate = $receivedDate;
        $this->receivedTime = $receivedTime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $part_no = Item::where('item_number', 'LIKE', $this->item . '%')->first();
        $location = Location::where('code', 'LIKE', 'L80%')->first();
        $transaction = TransactionType::where('code', 'LIKE', 'U%')->first();

        $date = str_replace('/', '-', $this->receivedDate);
        $time = $this->receivedTime;

        /**
         * Inventory
         */
        // $inventory = Inventory::where([
        //     ['item_id', '=', $part_no->id],
        //     ['location_id', '=', $location->id]
        // ])->first();

        // $inventoryQuantity = $inventory->quantity ?? 0;
        // $sum = $inventoryQuantity + $this->snp;

        // if ($inventory !== null) {
        //     Log::info('Item ID: ' . $part_no->id . ' No Part: ' . $this->item . ' Sum: ' . $sum . ' IF');
        //     $inventory->update(['quantity' => $sum]);
        // } else {
        //     Log::info('Item ID: ' . $part_no->id . ' No Part: ' . $this->item . ' Sum: ' . $sum . ' ELSE');
        //     Inventory::create([
        //         'item_id' =>  $part_no->id,
        //         'location_id' => $location->id,
        //         'quantity' => $sum
        //     ]);
        // }

        InputSupplier::create([
            'supplier' => $this->supplier,
            'order_no' => $this->orderNo,
            'sequence' => $this->sequence,
            'item_id' => $part_no->id,
            'snp' => $this->snp,
            'received_date' => $date,
            'received_time' => Carbon::parse($time)->format('H:i:s.v'),
            'user_id' => Auth::id(),
            'location_id' => $location->id,
            'transaction_type_id' => $transaction->id,
        ]);
    }
}
