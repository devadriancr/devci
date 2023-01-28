<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'iid',
        'item_number',
        'item_description',
        'safety_stock',
        'measurement_type_id',
        'item_type_id',
        'item_class_id',
        'standard_pack_id'
    ];

    /**
     *
     */
    public function measurementType(): BelongsTo
    {
        return $this->belongsTo(MeasurementType::class);
    }

    /**
     *
     */
    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class);
    }

    /**
     *
     */
    public function itemClass(): BelongsTo
    {
        return $this->belongsTo(ItemClass::class);
    }

    /**
     *
     */
    public function standardPack(): BelongsTo
    {
        return $this->belongsTo(StandardPack::class);
    }

    /**
     *
     */
    public function inventorIES(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     *
     */
    public function output(): HasMany
    {
        return $this->hasMany(Output::class);
    }

    /**
     *
     */
    public function input(): HasMany
    {
        return $this->hasMany(Input::class);
    }

    /**
     *
     */
    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     *
     */
    public function inputSupplier(): HasMany
    {
        return $this->hasMany(InputSupplier::class);
    }
}
