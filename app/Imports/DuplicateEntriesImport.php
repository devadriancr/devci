<?php

namespace App\Imports;

use App\Jobs\RemoveDuplicateInputJob;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DuplicateEntriesImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            $supplier = substr($row['serial'], 0, 5);
            $serial = substr($row['serial'], -9, 9);

            RemoveDuplicateInputJob::dispatch($supplier, $serial);
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
