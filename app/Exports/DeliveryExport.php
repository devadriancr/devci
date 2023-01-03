<?php

namespace App\Exports;

use App\Models\WherehouseInOut;
use App\Models\Input;
use App\Models\Output;
use App\Models\Travel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\DeliveryProduction;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithProperties;

class DeliveryExport implements FromView, ShouldAutoSize
{
    private $id; // declaras la propiedad
    public function __construct($id)
    {
        // $this->id = $id;
        $this->id = $id;
    }




    public function view(): View
    {
        $scan = Input::with('item')->where('delivery_production_id', $this->id)->get();
        $travel = DeliveryProduction::with('location')->find($this->id);

        return view('WhereHouse_In_Out.ReportScanDelivery', [
            'scan' => $scan,
            'travel' => $travel
        ]);
    }
}
