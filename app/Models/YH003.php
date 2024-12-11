<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class YH003 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-lx834f02';
    protected $table = 'LX834FU0.YH003';

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

    /**
     *
     */
    public static function store($item, $supplier, $serial, $snp)
    {
        YH003::query()->insert([
            'H3PROD' => $item->item_number,
            'H3SUCD' => $supplier,
            'H3SENO' => $serial,
            'H3RQTY' => $snp,
            'H3CUSR' => Auth::user()->user_infor ?? '',
            'H3RDTE' => now()->format('Ymd'),
            'H3RTIM' => now()->format('His')
        ]);
    }
}
