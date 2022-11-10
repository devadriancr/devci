<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'trans_mode', 'ct_no', 'ct_gr', 'invoice_no', 'module_no', 'parts_no', 'clr', 'parts_qty', 'vanning', 'time',
    ];
}
