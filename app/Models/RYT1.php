<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RYT1 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-lx834fu02';
    protected $table = 'LX834FU02.RYT1';

    // protected $fillable = [
    //     'TID', 'TTYPE', 'TDESC'
    // ];
}
