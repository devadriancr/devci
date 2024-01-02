<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OldInventory extends Model
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

    public static function storeOldInventory(float $openingBalance, float $quantity, int $itemId, int $locationId)
    {
        $oldInventory = OldInventory::create([
            'opening_balance' => $openingBalance,
            'quantity' => $quantity,
            'item_id' => $itemId,
            'location_id' => $locationId,
        ]);
    }
}
