<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignmentInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier', 'serial', 'part_no', 'part_qty', 'container_id', 'user_id'
    ];
}
