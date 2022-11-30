<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ILM extends Model
{
    use HasFactory;

    protected $connection = 'odbc-lx834f02';
    protected $table = 'LX834F02.ILM';

    protected $fillable = [
        'WID', 'WWHS', 'WLOC', 'WDESC'
    ];
}
