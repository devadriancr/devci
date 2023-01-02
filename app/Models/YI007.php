<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YI007 extends Model
{
    use HasFactory;
    protected $connection = 'odbc-lx834fu02';
    protected $table = 'LX834FU02.YI007';
    // live
    // protected $connection = 'odbc-lx834fu01';
    // protected $table = 'LX834FU02.YI001';
    protected $fillable = [
        'I7PROD',
        'I7SENO',
        'I7TFLG',
        'I7TDTE',
        'I7TTIM',
        'I7TQTY',
        'I7CUSR',
        'I7CCDT',
        'I7CCTM'

    ];

}
