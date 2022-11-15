<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingInOut extends Model
{
    use HasFactory;
    protected $fillable = [
'usuario',
'fecha_shi',
'hora_shi',
'transfer_flag',
'Wharehouse',
     ];
}
