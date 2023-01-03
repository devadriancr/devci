<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IWM extends Model
{
    use HasFactory;

    protected $connection = 'odbc-lx834f01';
    protected $table = 'LX834F01.IWM';

    protected $fillable = [
        'LID', 'LWHS', 'LDESC',
    ];
}
