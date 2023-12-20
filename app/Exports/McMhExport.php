<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class McMhExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $formattedData = collect($this->data)->map(function ($item) {
            $item['serialco'] = $item['supplier'] . ' ' . $item['serial'];
            $item['parte'] = $item['item_number'];
            $item['cant'] = $item['item_quantity'];
            $item['typeco'] = $item['type_consignment'];
            $item['contenedor'] = $item['container_code'];
            $item['transaccion'] = $item['transaction_code'] . ' ' . $item['transaction_name'];
            $item['locat'] = $item['location_code'] . ' ' . $item['location_name'];
            $item['fecha'] = $item['created_at'];

            unset($item['supplier']);
            unset($item['serial']);
            unset($item['item_number']);
            unset($item['item_quantity']);
            unset($item['type_consignment']);
            unset($item['container_code']);
            unset($item['transaction_code']);
            unset($item['transaction_name']);
            unset($item['location_code']);
            unset($item['location_name']);
            unset($item['created_at']);

            return $item;
        });

        return $formattedData;
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
            'FECHA DE REGISTRO',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
