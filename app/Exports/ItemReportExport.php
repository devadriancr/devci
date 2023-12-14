<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $selectedItems;

    public function __construct($selectedItems)
    {
        $this->selectedItems = $selectedItems;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->selectedItems->map(function ($item) {
            return [
                'serial' => $item->supplier .  $item->serial,
                'item_number' => $item->item_number,
                'item_quantity' => $item->item_quantity,
                'type_consignment' => $item->type_consignment,
                'container_code' => $item->container_code,
                'transaction' => $item->transaction_code . ' ' . $item->transaction_name,
                'location' => $item->location_code . ' ' . $item->location_name,
                'created_at' => $item->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'SERIAL',
            'NO. PARTE',
            'CANTIDAD',
            'TIPO DE CONSIGNA',
            'CONTENEDOR',
            'TRANSACCIÓN',
            'LOCACIÓN',
            'FECHA',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
