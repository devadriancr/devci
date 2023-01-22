<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryProduction extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // relacion 1:n de outputs
    public function output(): HasMany
    {
        return $this->hasMany(Output::class);
    }
    public function outputsupplier(): HasMany
    {
        return $this->hasMany(outputsupplier::class);
    }
    public function input(): HasMany
    {
        return $this->hasMany(Input::class);
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
