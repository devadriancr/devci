<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'lid', 'code', 'name', 'description', 'status',
    ];

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
