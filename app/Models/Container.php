<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Container extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'code', 'arrival_date', 'arrival_time', 'status',
    ];

    public function intputs(): HasMany
    {
        return $this->hasMany(Input::class);
    }

    public static function storeContainer(string $container, string $arrival_date, string $arrival_time)
    {
        Container::create([
            'code' => strtoupper($container),
            'arrival_date' => strtoupper($arrival_date),
            'arrival_time' => strtoupper($arrival_time),
        ]);
    }
}
