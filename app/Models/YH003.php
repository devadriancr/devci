<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YH003 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-lx834fu01';
    protected $table = 'LX834FU01.YH003';

    protected $fillable = [
        'H3SINO',
        'H3CONO',
        'H3DDTE',
        'H3DTIM',
        'H3PROD',
        'H3SUCD',
        'H3SPCD',
        'H3SENO',
        'H3RQTY',
        'H3RDTE',
        'H3RTIM',
        'H3CUSR',
        'H3CCDT',
        'H3CCTM',
    ];
}
