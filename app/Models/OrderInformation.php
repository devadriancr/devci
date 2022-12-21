<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderInformation extends Model
{
    use HasFactory;
    protected $guarded=['id','created_at','updated_at'];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }

    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function travel(): BelongsTo
    {
        return $this->belongsTo(travel::class);
    }
    public function user():  BelongsTo
    {
        return $this->belongsTo(user::class);
    }
}
