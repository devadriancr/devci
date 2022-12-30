<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'opening_balance',
        'quantity',
        'item_id',
        'location_id'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public static function storeInventory(int $itemId, float $quantity, int $locationId, float $openingBalance)
    {
        $data = Inventory::create(
            [
                'item_id' => $itemId,
                'quantity' => $quantity,
                'location_id' => $locationId,
                'opening_balance' => $openingBalance
            ]
        );
    }
}
