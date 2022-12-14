<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YH005 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-lx834fu01';
    protected $table = 'LX834FU01.YH005';

    protected $fillable = [
        'H5CPRO',
        'H5FPRO',
        'H5TDTE',
        'H5RQTY',
        'H5UQTY',
        'H5BREQ',
        'H5DT00',
        'H5QY00',
        'H5DT01',
        'H5QY01',
        'H5DT02',
        'H5QY02',
        'H5DT03',
        'H5QY03',
        'H5CUSR',
        'H5CCDT',
        'H5CCTM',
    ];
}
