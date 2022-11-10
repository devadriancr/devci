<?php

namespace App\Imports;

use App\Models\ShippingInstruction;
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
            'trans_mode' => $row['trans_mode'],
            'ct_no' => $row['ct_no'],
            'ct_gr' => $row['ct_gr'],
            'invoice_no' => $row['invoice_no'],
            'module_no' => $row['module_no'],
            'parts_no' => $row['parts_no'],
            'clr' => $row['clr'],
            'parts_qty' => $row['parts_qty'],
            'vanning' => $row['vanning'],
            'time' => $row['time'],
        ]);
    }

    public function batchSize(): int
    {
        return 2000;
    }

    public function chunkSize(): int
    {
        return 2000;
    }
}
