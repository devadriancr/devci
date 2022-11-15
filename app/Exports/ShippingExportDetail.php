<?php

namespace App\Exports;

use App\Models\WherehouseInOut;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ShippingExportDetail implements FromView
{
    private $id; // declaras la propiedad
    private $fecha;
    private $fechaFin;
    public function __construct( $id )
    {
        // $this->id = $id;
        $this->id = $id;
    }
    public function view(): View
    {
        $scan =  WherehouseInOut::query()->select('serial', 'part_no', 'part_qty', 'date_Scan', 'time_scan', 'status', 'shippign')
        ->where('shippign', '=',$this->id)->get();
        return view('WhereHouse_In_Out.ReportScanDetail', [
            'scan' => $scan
        ]);
    }
}
// class UsersExport implements FromView
// {
//     /**
//      * @return \Illuminate\Support\Collection
//      */
//     public function headings(): array
//     {
//         return [
//             'final',
//             'componente',
//             'clase',
//             'Activo'
//         ];
//     }

//     public function view(): view
//     {
//         return view();
//     }
// }
