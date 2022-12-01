<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Input extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier',
        'serial',
        'item_id',
        'item_quantity',
        'container_id',
        'transaction_type_id',
        'location_id',
        'travel_id',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function transactiontype(): BelongsTo
    {
        return $this->belongsTo(Transactiontype::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }
}
