<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item', 'description', 'opening_balance', 'minimum_balance','maximum_balance', 'status', 'item_type', 'item_class', 'measurement_unit', 'creation_date', 'creation_time'
    ];
}
