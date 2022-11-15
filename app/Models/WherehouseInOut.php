<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WherehouseInOut extends Model
{
    use HasFactory;
    protected $fillable = [
       'serial', 'part_no', 'part_qty','date_Scan','time_scan','status',
       'shippign'
    ];
}
