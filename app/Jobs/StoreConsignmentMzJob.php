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

class StoreConsignmentMzJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $serial;
    private $part_no;
    private $snp;
    private $supplier;
    private $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($serial, $part_no, $snp, $supplier, $type)
    {
        $this->serial = $serial;
        $this->part_no = $part_no;
        $this->snp = $snp;
        $this->supplier = $supplier;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Entro MZ");
        $item = Item::where('item_number', 'LIKE', $this->part_no . '%')->first();
        $transaction = TransactionType::where('code', 'LIKE', 'U3')->first();
        $location = Location::where('code', 'LIKE', 'L60%')->first();

        $input = Input::create([
            'supplier' => $this->supplier,
            'serial' => $this->serial,
            'item_id' => $item->id,
            'item_quantity' => $this->snp,
            'type_consignment' => $this->type,
            'transaction_type_id' => $transaction->id,
            'location_id' => $location->id,
            'user_id' => Auth::id()
        ]);

        // $yh003 = YH003::query()->insert([
        //     'H3PROD' => $item->item_number,
        //     'H3SUCD' => $this->supplier,
        //     'H3SENO' => $this->serial,
        //     'H3RQTY' => $this->snp,
        //     'H3CUSR' => Auth::user()->user_infor ?? '',
        //     'H3RDTE' => Carbon::parse($input->created_at)->format('Ymd'),
        //     'H3RTIM' => Carbon::parse($input->created_at)->format('His')
        // ]);

        $inventory = Inventory::where([
            ['item_id', '=', $item->id],
            ['location_id', '=', $location->id]
        ])->first();

        if ($inventory !== null) {
            $qty = $inventory->quantity ?? 0;

            $sum = 0;
            $sum = $this->snp + $qty;

            $inventory->update(['quantity' => $sum]);
        } else {
            $inventory = Inventory::create([
                'item_id' => $item->id,
                'location_id' => $location->id,
                'quantity' => $this->snp
            ]);
        }
    }
}
