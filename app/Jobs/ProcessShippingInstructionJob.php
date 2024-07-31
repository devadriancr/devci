<?php

namespace App\Jobs;

use App\Models\ShippingInstruction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProcessShippingInstructionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $row;
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($row, $userId)
    {
        $this->row = $row;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $row = $this->row;

        try {
            if (is_numeric($row['delivery_date'])) {
                $arrival_date = Carbon::instance(Date::excelToDateTimeObject($row['delivery_date']))->format('Y-m-d');
                $arrival_time = Carbon::instance(Date::excelToDateTimeObject($row['time']))->format('H:i');
            } else {
                $arrival_date = Carbon::createFromFormat('d/m/Y', $row['delivery_date'])->format('Y-m-d');
                $arrival_time = Carbon::createFromFormat('H:i', $row['time'])->format('H:i');
            }
        } catch (\Exception $e) {
            return;
        }

        $shipping = ShippingInstruction::where([
            ['serial', $row['module_no']],
            ['part_no', $row['parts_no']],
            ['container', $row['ct_no']]
        ])->first();

        if ($shipping === null) {
            StoreShippingInstructionJob::dispatch($row, $this->userId, $arrival_date, $arrival_time);
        }
    }
}
