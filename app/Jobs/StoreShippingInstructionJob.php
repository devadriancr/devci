<?php

namespace App\Jobs;

use App\Models\ShippingInstruction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreShippingInstructionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $row;
    protected $userId;
    protected $arrival_date;
    protected $arrival_time;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($row, $userId, $arrival_date, $arrival_time)
    {
        $this->row = $row;
        $this->userId = $userId;
        $this->arrival_date = $arrival_date;
        $this->arrival_time = $arrival_time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ShippingInstruction::create([
            'container' => strval($this->row['ct_no']),
            'invoice' => strval($this->row['invoice_no']),
            'serial' => strval($this->row['module_no']),
            'part_no' => strval($this->row['parts_no']),
            'part_qty' => intval($this->row['parts_qty']),
            'arrival_date' => $this->arrival_date,
            'arrival_time' => $this->arrival_time,
            'user_id' => $this->userId
        ]);
    }
}
