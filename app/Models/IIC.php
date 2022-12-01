<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IIC extends Model
{
    use HasFactory;

    protected $connection = 'odbc-lx834f02';
    protected $table = 'LX834F02.IIC';

    protected $fillable = [
        'IID', 'ICLAS','ICDES'
    ];
}
