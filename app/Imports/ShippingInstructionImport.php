<?php

namespace App\Imports;

use App\Jobs\ProcessShippingInstruction;
use App\Jobs\ProcessShippingInstructionJob;
use App\Models\ShippingInstruction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        try {
            if (is_numeric($row['delivery_date'])) {
                $arrival_date = Carbon::instance(Date::excelToDateTimeObject($row['delivery_date']))->format('Y-m-d');
                $arrival_time = Carbon::instance(Date::excelToDateTimeObject($row['time']))->format('H:i');
            } else {
                $arrival_date = Carbon::createFromFormat('d/m/Y', $row['delivery_date'])->format('Y-m-d');
                $arrival_time = Carbon::createFromFormat('H:i', $row['time'])->format('H:i');
            }
        } catch (\Exception $e) {
            $this->invalidRows[] = $row;
            return null;
        }

        $shipping = ShippingInstruction::where([
            ['serial', $row['module_no']],
            ['part_no', $row['parts_no']],
            ['container', $row['ct_no']],
            ['arrival_date', $arrival_date],
            ['arrival_time', $arrival_time]
        ])->first();

        if ($shipping === null) {
            $this->processedRows++;
            return new ShippingInstruction([
                'container' => strval($row['ct_no']),
                'invoice' => strval($row['invoice_no']),
                'serial' => strval($row['module_no']),
                'part_no' => strval($row['parts_no']),
                'part_qty' => intval($row['parts_qty']),
                'arrival_date' => $arrival_date,
                'arrival_time' => $arrival_time,
                'user_id' => Auth::id()
            ]);
        }

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
