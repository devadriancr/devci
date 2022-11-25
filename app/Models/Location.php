<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'wid', 'code', 'name', 'description', 'status', 'warehouse_id',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    // relacion de 1:N input
    public function input(): HasMany
    {
        return $this->hasMany(Input::class);
    }
    // relacion de 1:N input
    public function output(): HasMany
    {
        return $this->hasMany(Output::class);
    }
}
