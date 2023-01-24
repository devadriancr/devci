<?php

namespace App\Imports;

use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\TransactionType;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class InventoryAmountOpeningBalanceImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $transaction = TransactionType::where('code', 'LIKE', 'O%')->first();
        $item = Item::where('item_number', 'LIKE', $row['item'] . '%')->first();
        $loct = Location::where('code', 'LIKE', $row['locacion'] . '%')->first();
        $qty = $row['qty'];

        if ($item !== null) {
            $inventory = Inventory::where(
                [
                    ['item_id', $item->id],
                    ['location_id', $loct->id]
                ]
            )->first();

            $input = Input::where(
                [
                    ['item_id', $item->id],
                    ['location_id', $loct->id],
                    ['transaction_type_id', $transaction->id]
                ]
            )->orderBy('id', 'DESC')->first();

            if ($inventory !== null) {
                $inventory->update(['opening_balance' => $qty]);
            } else {
                $inventory = Inventory::create([
                    'opening_balance' => $qty,
                    'item_id' => $item->id,
                    'location_id' => $loct->id
                ]);
            }

            if ($input !== null) {
                $input->update(['item_quantity' => $qty]);
            } else {
                $input = Input::storeOpeningBalance($item->id, $qty, $transaction->id, $loct->id);
            }
        }
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }
}
