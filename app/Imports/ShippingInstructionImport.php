<?php

namespace App\Imports;

use App\Jobs\ProcessShippingInstruction;
use App\Jobs\ProcessShippingInstructionJob;
use App\Models\ShippingInstruction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ShippingInstructionImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected $invalidRows = [];
    protected $totalRows = 0;
    protected $processedRows = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $this->totalRows++;

        if (empty($row['ct_no']) || empty($row['invoice_no']) || empty($row['module_no']) || empty($row['parts_no']) || empty($row['parts_qty']) || empty($row['delivery_date']) || empty($row['time'])) {
            $this->invalidRows[] = $row;
            return null;
        }

        ProcessShippingInstructionJob::dispatch($row, Auth::id());

        $this->processedRows++;
        return null;
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function getInvalidRows()
    {
        return $this->invalidRows;
    }

    public function getTotalRows()
    {
        return $this->totalRows;
    }

    public function getProcessedRows()
    {
        return $this->processedRows;
    }
}
