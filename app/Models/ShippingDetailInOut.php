<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingDetailInOut extends Model
{
    use HasFactory;
    protected $fillable = [
        'shipping_id', 'consignment_id','part_no', 'part_qty','date_Scan','time_scan','status',

     ];
}
