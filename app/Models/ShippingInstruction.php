<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'container', 'invoice', 'serial', 'part_no', 'part_qty', 'arrival_date', 'arrival_time', 'user_id'
    ];
}
