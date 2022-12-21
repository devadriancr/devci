<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'arrival_date', 'arrival_time', 'status',
    ];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }

     public function intputs():HasMany
     {
         return $this->hasMany(Input::class);
     }

     public static function storeContainer(string $container, string $arrival_date, string $arrival_time)
     {
        Container::create([
            'code' => $container,
            'arrival_date' => $arrival_date,
            'arrival_time' => $arrival_time,
        ]);
     }

}
