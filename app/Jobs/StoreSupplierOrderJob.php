<?php

namespace App\Jobs;

use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\TransactionType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StoreSupplierOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private $data;
    private $no_po;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($hpo, $po)
    {
        $this->data = $hpo;
        $this->no_po = $po;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info($this->data->PPROD);
        $transaction = TransactionType::where('code', 'LIKE', 'U%')->first();
        $location = Location::where('code', 'LIKE', 'L80%')->first();
        $item = Item::where('item_number', $this->data->PPROD)->first();

        $inventoryItem = Inventory::where([
            ['item_id', '=', $item->id],
            ['location_id', '=', $location->id]
        ])->first();

        $inventoryQuantity = $inventoryItem->quantity ?? 0;
        $sum = $inventoryQuantity + $this->data->PQREC;

        Input::create(
            [
                'item_id' => $item->id,
                'item_quantity' => $this->data->PQREC,
                'transaction_type_id' => $transaction->id,
                'location_id' => $location->id,
                'purchase_order' => $this->no_po,
                'user_id' => Auth::id()
            ]
        );

        Inventory::updateOrCreate(
            [
                'item_id' =>  $item->id,
                'location_id' => $location->id,
            ],
            [
                'quantity' => $sum
            ]
        );
    }
}
