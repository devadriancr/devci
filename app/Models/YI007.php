<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Cast\String_;

class YI007 extends Model
{
    use HasFactory;
    protected $connection = 'odbc-lx834fu01';
    protected $table = 'LX834FU01.YI007';

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

    public static function storeYI007(String $item_number, String $serial, String $flag, String $arrival_date, String $arrival_time, int $part_qty, String $warehouse, String $user, String $date, String $time)
    {
        YI007::Query()->insert(
            [
                'I7PROD' =>  $item_number ?? '',
                'I7SENO' => $serial ?? '',
                'I7TFLG' => $flag ?? '',
                'I7TDTE' => $arrival_date ?? '',
                'I7TTIM' => $arrival_time ?? '',
                'I7TQTY' => $part_qty ?? '',
                'I7WHS' =>  $warehouse ?? '',
                'I7CUSR' => $user ?? '',
                'I7CCDT' => $date ?? '',
                'I7CCTM' => $time ?? ''
            ]
        );
    }
}
