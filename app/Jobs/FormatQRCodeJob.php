<?php

namespace App\Jobs;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FormatQRCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $code_qr;
    protected $container_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($code_qr, $container_id)
    {
        $this->code_qr = $code_qr;
        $this->container_id = $container_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dataRequest = strtoupper($this->code_qr);
        $dataParts = explode(',', $dataRequest);

        $supplier = $dataParts[11];
        $serial = $dataParts[13];
        $part_no = end($dataParts);
        $part_qty = $dataParts[10];

        $data = ConsignmentInstruction::where(
            [
                ['supplier', $supplier],
                ['serial', $serial],
                ['part_no', 'LIKE', $part_no . '%'],
                ['container_id', $this->container_id]
            ]
        )->first();

        if (is_null($data)) {
            CheckQRCodeRegistrationJob::dispatch($supplier, $serial, $part_no, $part_qty, $this->container_id);
        }
    }
}
