<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RYT1 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-lx834fu01';
    protected $table = 'LX834FU01.RYT1';

    // protected $fillable = [
    //     'TID', 'TTYPE', 'TDESC'
    // ];
}
