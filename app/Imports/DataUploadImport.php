<?php

namespace App\Imports;

use App\Models\ConsignmentInstruction;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataUploadImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new ConsignmentInstruction([
            'part_qty' => strtoupper($row['quantity']),
            'supplier' => strtoupper($row['supplier']),
            'serial' => strtoupper($row['serial']),
            'part_no' => strtoupper($row['part_no']),
            'location' => strtoupper($row['location']),
            'user_id' => Auth::id()
        ]);
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
