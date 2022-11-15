<?php

namespace App\Exports;

use App\Models\WherehouseInOut;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ShippingExport implements FromView
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
        $scan =  WherehouseInOut::query()->selectRaw('SUM(part_qty) as Total,part_no,  date_Scan,  shippign')
        ->where('shippign', '=',$this->id)->groupby('part_no','date_Scan','shippign')->get();
        return view('WhereHouse_In_Out.ReportScan', [
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
