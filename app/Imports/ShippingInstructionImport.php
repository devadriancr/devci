<?php

namespace App\Imports;

use App\Models\ShippingInstruction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ShippingInstructionImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($row['delivery_date'] != '#N/D') {
            return new ShippingInstruction([
                'container' => strval($row['ct_no']),
                'invoice' => strval($row['invoice_no']),
                'serial' => strval($row['module_no']),
                'part_no' => strval($row['parts_no']),
                'part_qty' => $row['parts_qty'],
                'arrival_date' => $row['delivery_date'],
                'arrival_time' => $row['time'],
                'user_id' => Auth::id()
            ]);
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
