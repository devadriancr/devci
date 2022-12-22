<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class output extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $guarded = ['id', 'created_at', 'updated_at'];

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     /**
     *
     */
    public  static function storeOutput(int $item, string $quantity, int $transaction, int $location)
    {
        output::create(
            [
                'item_id' => $item,
                'item_quantity' => $quantity,
                'transaction_type_id' => $transaction,
                'location_id' => $location,
                'user_id' => Auth::id()
            ]
        );
    }
}
