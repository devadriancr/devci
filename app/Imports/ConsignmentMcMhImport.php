<?php

namespace App\Imports;

use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\TransactionType;
use App\Models\YH003;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ConsignmentMcMhImport implements OnEachRow, WithHeadingRow
{
    public $total = 0;
    public $imported = 0;
    public $duplicated = 0;
    public $errors = [];

    public function onRow(Row $row)
    {
        $rowNumber = $row->getIndex();
        $row = $row->toArray();

        if (empty($row['code'])) {
            return;
        }

        $this->total++;

        $code = strtoupper(trim($row['code']));

        if (strlen($code) < 30 || strlen($code) > 35) {
            $this->errors[] = "Fila {$rowNumber}: el código debe tener entre 30 y 35 caracteres.";
            return;
        }

        $date = $this->parseDate($row['date'] ?? null);

        if (is_null($date)) {
            $this->errors[] = "Fila {$rowNumber}: la fecha no es válida.";
            return;
        }

        $no_order = substr($code, 0, 7);
        $serial = substr($code, 0, 10);
        $part_no = substr($code, 10, 10);
        $snp = substr($code, 20, 6);
        $supplier = substr($code, 26, 5);
        $type = substr($code, 31, 2);

        $existing = Input::findExistingInput($supplier, $serial, $snp, $type, $no_order);

        if ($existing) {
            $this->duplicated++;
            return;
        }

        $item = Item::where('item_number', 'LIKE', $part_no . '%')->first();

        if (is_null($item)) {
            $this->errors[] = "Fila {$rowNumber}: no se encontró el número de parte {$part_no}.";
            return;
        }

        try {
            $transaction = TransactionType::where('code', 'LIKE', 'U3')->firstOrFail();
            $location = Location::where('code', 'LIKE', 'L60%')->firstOrFail();

            $input = new Input([
                'no_order' => $no_order,
                'supplier' => $supplier,
                'serial' => $serial,
                'item_id' => $item->id,
                'item_quantity' => $snp,
                'type_consignment' => $type,
                'transaction_type_id' => $transaction->id,
                'location_id' => $location->id,
                'user_id' => Auth::id()
            ]);

            $input->created_at = $date;
            $input->updated_at = $date;
            $input->save();

            YH003::store($item, $supplier, $serial, $snp, $date);

            Inventory::updateInventory($item->id, $location->id, $snp);

            $this->imported++;
        } catch (\Throwable $e) {
            Log::error('Error al importar consigna MC/MH', [
                'row' => $rowNumber,
                'code' => $code,
                'error' => $e->getMessage()
            ]);
            $this->errors[] = "Fila {$rowNumber}: error al registrar el código.";
        }
    }

    /**
     * Convierte el valor de la columna DATE (número de serie de Excel o texto) a Carbon.
     */
    private function parseDate($value): ?Carbon
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value));
            }

            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
