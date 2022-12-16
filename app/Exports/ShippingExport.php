<?php

namespace App\Exports;

use App\Models\WherehouseInOut;
use App\Models\Input;
use App\Models\Output;
use App\Models\Travel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class ShippingExport implements FromView,ShouldAutoSize
{
    private $id; // declaras la propiedad
    public function __construct($id)
    {
        // $this->id = $id;
        $this->id = $id;
    }
    public function view(): View
    {
        $scan = Input::with('item')->where('travel_id', $this->id)->exists();
        $travel=travel::with('location')->find($this->id);
        if ($scan == false) {
            $scan = Output::with('item')->where('travel_id', $this->id)->get();
        } else {
            $scan = Input::with('item')->where('travel_id', $this->id)->get();
        }
        return view('WhereHouse_In_Out.ReportScan', [
            'scan' => $scan,
            'travel'=>$travel
        ]);
    }
}
