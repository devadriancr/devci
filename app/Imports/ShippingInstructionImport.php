<?php

namespace App\Imports;

use App\Models\ShippingInstruction;
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
        return new ShippingInstruction([
            'container' => $row['ct_no'],
            'invoice' => $row['invoice_no'],
            'serial' => $row['module_no'],
            'part_no' => $row['parts_no'],
            'part_qty' => $row['parts_qty'],
            'user_id' => Auth::id()
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
