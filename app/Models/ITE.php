<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ITE extends Model
{
    use HasFactory;

    protected $connection = 'odbc-lx834f01';
    protected $table = 'LX834F01.ITE';

    protected $fillable = [
        'TID', 'TTYPE', 'TDESC'
    ];
}
