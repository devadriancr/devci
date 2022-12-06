<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'arrival_date', 'arrival_time', 'status',
    ];

     public function intputs():HasMany
     {
         return $this->hasMany(Input::class);
     }

}
