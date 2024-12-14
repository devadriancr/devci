<?php

namespace App\Jobs;

use App\Models\ConsignmentInstruction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScannedMaterialJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('Procesando material escaneado:', $this->data);

        $part_no = $this->data['part_no'];
        $part_qty = $this->data['part_qty'];
        $supplier = $this->data['supplier'];
        $serial = $this->data['serial'];
        $container_id = $this->data['container_id'];

        $data = ConsignmentInstruction::where(
            [
                ['supplier', $supplier],
                ['serial', $serial],
                ['part_no', 'LIKE', $part_no . '%'],
                ['container_id', $container_id]
            ]
        )->first();

        if (is_null($data)) {
            CheckQRCodeRegistrationJob::dispatch($supplier, $serial, $part_no, $part_qty, $container_id);
        }
    }
}
