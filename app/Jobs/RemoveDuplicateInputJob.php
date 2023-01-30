<?php

namespace App\Jobs;

use App\Models\Input;
use App\Models\Inventory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveDuplicateInputJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $supplier;
    private $serial;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($supplier, $serial)
    {
        $this->supplier = $supplier;
        $this->serial = $serial;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $inputs = Input::where(
            [
                ['supplier', 'LIKE', $this->supplier],
                ['serial', 'LIKE', $this->serial],
                ['location_id', 328],
                ['transaction_type_id', 82]
            ]
        )->orderBy('id', 'DESC')->get();

        foreach ($inputs as $key => $input) {
            $qty = 0;
            if ($key != 0) {
                $inventory = Inventory::where([['item_id', $input->item_id], ['location_id', 328]])->first();
                $qty = $inventory->quantity - $input->item_quantity;
                $inventory->update(['quantity' => $qty]);
                $input->delete();
            }
        }
    }
}
