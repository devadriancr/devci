<?php

namespace App\Exports;

use App\Models\WherehouseInOut;
use App\Models\Input;
use App\Models\Output;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class OrderDetailExport implements FromView,ShouldAutoSize
{
    private $id; // declaras la propiedad
    public function __construct($id)
    {
        // $this->id = $id;
        $this->id = $id;
    }
    public function view(): View
    {
        $orden=order::with('item')->where('orden_information_id',$this->id)->get();
        return view('WhereHouse_In_Out.OrderDetail', [
            'order'=>$orden
        ]);
    }
}
