<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IIM extends Model
{
    use HasFactory;

    protected $connection = 'odbc-lx834f01';
    protected $table = 'LX834F01.IIM';

    protected $fillable = [
        'IID',
        'IPROD',
        'IDESC',
        'IOPB',
        'IMIN',
        'IITYP',
        'ICLAS',
        'IUMS',
        'IMENDT',
        'IMENTM',
    ];
}
