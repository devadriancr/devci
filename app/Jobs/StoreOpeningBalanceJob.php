<?php

namespace App\Jobs;

use App\Models\Input;
use App\Models\InputSupplier;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\OldInventory;
use App\Models\TransactionType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StoreOpeningBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $item, $location, $openingBalance;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($item, $location, $openingBalance)
    {
        $this->item = $item;
        $this->location = $location;
        $this->openingBalance = $openingBalance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $item = Item::where('item_number', $this->item)->first();
        $location = Location::where('code', $this->location)->first();

        if ($item !== null && $location !== null) {
            $transaction = TransactionType::where('code', 'LIKE', '%O%')->first();

            $data = Inventory::where(
                [
                    ['item_id', $item->id],
                    ['location_id', $location->id]
                ]
            )->first();

            if ($data !== null) {
                if ($data->item->itemClass->code == 'S1') {
                    Input::storeOpeningConsignment($item->id,  $this->openingBalance, $transaction->id, $location->id);

                    OldInventory::storeOldInventory($data->opening_balance, $data->quantity, $data->item_id, $data->location_id);

                    $data->update(['opening_balance' => $this->openingBalance, 'quantity' => 0]);
                } else if ($data->item->itemClass->code == 'P0' || $data->item->itemClass->code == 'P1' || $data->item->itemClass->code == 'P2') {
                    InputSupplier::storeOpeningSupplier($item->id,  $this->openingBalance, $transaction->id, $location->id);

                    OldInventory::storeOldInventory($data->opening_balance, $data->quantity, $data->item_id, $data->location_id);

                    $data->update(['opening_balance' => $this->openingBalance, 'quantity' => 0]);
                } else if ($data->item->itemClass->code == 'G0' || $data->item->itemClass->code == 'G1') {
                    InputSupplier::storeOpeningSupplier($item->id,  $this->openingBalance, $transaction->id, $location->id);

                    OldInventory::storeOldInventory($data->opening_balance, $data->quantity, $data->item_id, $data->location_id);

                    $data->update(['opening_balance' => $this->openingBalance, 'quantity' => 0]);
                } else {
                    OldInventory::storeOldInventory($data->opening_balance, $data->quantity, $data->item_id, $data->location_id);

                    $data->update(['opening_balance' => $this->openingBalance, 'quantity' => 0]);
                }
            } else {
                $data = Inventory::create(
                    [
                        'item_id' => $item->id,
                        'location_id' => $location->id,
                        'opening_balance' => $this->openingBalance,
                    ]
                );

                if ($data->item->itemClass->code == 'S1') {
                    $input = Input::storeOpeningConsignment($item->id, $this->openingBalance, $transaction->id, $location->id);
                } else if ($data->item->itemClass->code == 'P0' || $data->item->itemClass->code == 'P1' || $data->item->itemClass->code == 'P2') {
                    $input = InputSupplier::storeOpeningSupplier($item->id,  $this->openingBalance, $transaction->id, $location->id);
                } else if ($data->item->itemClass->code == 'G0' || $data->item->itemClass->code == 'G1') {
                    $input = InputSupplier::storeOpeningSupplier($item->id,  $this->openingBalance, $transaction->id, $location->id);
                }
            }
        } else {
            Log::info("Item: " . $this->item . "Locación: " . $this->location);
        }
    }
}
