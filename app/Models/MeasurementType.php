<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeasurementType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
