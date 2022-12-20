<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Order extends Model
{
    use HasFactory;
    protected $guarded=['id','created_at','updated_at'];
    // relacion 1:n de outputs
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
    // relacion de 1:N inversa travel
    public function orderinformation(): BelongsTo
    {
        return $this->belongsTo(orderinformation::class);
    }

}
