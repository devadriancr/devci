<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InputSupplier extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'supplier',
        'order_no',
        'sequence',
        'item_id',
        'snp',
        'received_date',
        'received_time',
        'user_id',
        'location_id',
        'transaction_type_id',
        'delivery_production_id',
        'travel_id',
        'payload'
    ];

    /**
     *
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     *
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transactiontype::class, 'transaction_type_id');
    }

    /**
     *
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     *
     */
    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }

    /**
     *
     */
    public function deliveryproduction(): BelongsTo
    {
        return $this->belongsTo(DeliveryProduction::class);
    }

    /**
     *
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
