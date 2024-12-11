<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YH003Failure extends Model
{
    use HasFactory;

    protected $table = 'yh003_failures';

    protected $fillable = [
        'H3CONO',
        'H3DDTE',
        'H3DTIM',
        'H3PROD',
        'H3SUCD',
        'H3SENO',
        'H3RQTY',
        'H3CUSR',
        'H3RDTE',
        'H3RTIM',
        'status'
    ];
}
