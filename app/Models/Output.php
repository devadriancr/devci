<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class output extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }

    // relacion de 1:N inversa item
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
    // relacion de 1:N inversa travel
    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }
    // relacion de 1:N inversa transaction type
    public function transactiontype(): BelongsTo
    {
        return $this->belongsTo(transactiontype::class, 'transaction_type_id');
    }
    // relacion de 1:N inversa location
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function deliveryproduction(): BelongsTo
    {
        return $this->belongsTo(delveryproduction::class);
    }
}
