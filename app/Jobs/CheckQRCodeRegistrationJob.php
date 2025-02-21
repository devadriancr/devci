<?php

namespace App\Jobs;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\ShippingInstruction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckQRCodeRegistrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $supplier, $serial, $part_no, $part_qty, $container_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($supplier, $serial, $part_no, $part_qty, $container_id)
    {
        $this->supplier = $supplier;
        $this->serial = $serial;
        $this->part_no = $part_no;
        $this->part_qty = $part_qty;
        $this->container_id = $container_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $container = Container::find($this->container_id);

        $shipping = ShippingInstruction::query()
            ->where(
                [
                    ['container', 'LIKE', $container->code],
                    ['arrival_date', $container->arrival_date],
                    ['arrival_time', $container->arrival_time],
                    ['part_no', 'LIKE', $this->part_no . '%'],
                    ['serial', 'LIKE', $this->supplier . $this->serial]
                ]
            )->first();

        if ($shipping) {
            Log::alert("CheckQRCodeRegistrationJob", ['supplier' => $this->supplier, 'serial' => $this->serial, 'part_no' =>  $this->part_no, 'part_qty' => $this->part_qty, 'container_id' =>  $this->container_id]);
            StoreConsignmentMaterialJob::dispatch($this->supplier, $this->serial,  $this->part_no, $this->part_qty,  $this->container_id);
        } else {
            Log::alert(
                "No se encontro en Shipping : ",
                [
                    'serial' => $this->supplier . $this->serial,
                    'part_no' => $this->part_no
                ]
            );
        }
    }
}
