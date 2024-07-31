<?php

namespace App\Imports;

use App\Models\ConsignmentInstruction;
use App\Models\Container;
use App\Models\ShippingInstruction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class QRCodeConsignmentImport implements ToModel, WithHeadingRow, WithEvents
{

    use RegistersEventListeners;

    private $total = 0;
    private $imported = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $this->total++;

        $qrCode = strtoupper(trim($row['qr_code']));
        $container = strtoupper(trim($row['container']));
        $date = is_numeric($row['date']) ? Carbon::instance(Date::excelToDateTimeObject($row['date']))->format('Y-m-d') : null;
        $time = is_numeric($row['time']) ? Carbon::instance(Date::excelToDateTimeObject($row['time']))->format('H:i') : null;

        $containerData = Container::where([['code', $container], ['arrival_date', $date], ['arrival_time', $time]])->first();
        $dataParts = explode(',', $qrCode);

        $part_qty = $dataParts[10] ?? null;
        $supplier = $dataParts[11] ?? null;
        $serial = $dataParts[13] ?? null;
        $part_no = end($dataParts);

        if ($containerData) {
            $data = ConsignmentInstruction::where(
                [
                    ['supplier', $supplier],
                    ['serial', $serial],
                    ['part_no', 'LIKE', $part_no . '%'],
                    ['container_id', $containerData->id],
                ]
            )->first();

            if (is_null($data)) {
                $shipping = ShippingInstruction::query()->where(
                    [
                        ['container', 'LIKE', $containerData->code],
                        ['arrival_date', $containerData->arrival_date],
                        ['arrival_time', $containerData->arrival_time],
                        ['part_no', 'LIKE', $part_no . '%'],
                        ['serial', 'LIKE', $supplier . $serial],
                    ]
                )->first();

                if ($shipping) {
                    ConsignmentInstruction::storeConsignment($serial, $supplier, $part_qty, $part_no, 'L60', $containerData->id);
                    $this->imported++;
                }
            }
        }
    }

    public static function afterImport(\Maatwebsite\Excel\Events\AfterImport $event)
    {
        $importer = $event->getConcernable();
        session()->flash('import_summary', [
            'total' => $importer->total,
            'imported' => $importer->imported,
        ]);
    }
}
