<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $dateFormat='Ymd H:i:s.v';

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

    public function inputs(): HasMany
    {
        return $this->hasMany(Input::class);
    }

    // relacion de 1:N input
    public function output(): HasMany
    {
        return $this->hasMany(Output::class);
    }
    // relacion de 1:N Travel
    public function travel(): HasMany
    {
        return $this->hasMany(Travel::class);
    }
     // relacion de 1:N delivery
     public function deliveryproduction(): HasMany
     {
         return $this->hasMany(deliveryproduction::class);
     }
}
