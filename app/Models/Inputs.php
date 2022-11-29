<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class inputs extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    // relacion de 1:N inversa travel
    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }

    // relacion de 1:N inversa item
    public function item():BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    // relacion de 1:N inversa transaction type
    public function transactiontype():BelongsTo
    {
        return $this->belongsTo(Transactiontype::class);
    }
     // relacion de 1:N inversa location
     public function location ():BelongsTo
     {
         return $this->belongsTo(Location::class);
     }
     // relacion de 1:N inversa cointainer
     public function container ():BelongsTo
     {
         return $this->belongsTo(Container::class);
     }
}
