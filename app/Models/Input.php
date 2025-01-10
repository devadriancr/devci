<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Input extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'supplier',
        'serial',
        'item_id',
        'item_quantity',
        'container_id',
        'transaction_type_id',
        'delivery_production_id',
        'location_id',
        'travel_id',
        'user_id',
        'type_consignment',
        'no_order'
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
    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
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

    /**
     *
     */
    public static function storeOpeningConsignment(int $itemId, float $quantity, int $transactionId, int $locationId)
    {
        $input = Input::create(
            [
                'item_id' => $itemId,
                'item_quantity' => $quantity,
                'transaction_type_id' => $transactionId,
                'location_id' => $locationId,
                'user_id' => Auth::id()
            ]
        );
    }

    /**
     *
     */
    public static function storeInputConsignment(
        string $supplier,
        string $serial,
        int $itemId,
        string $item,
        int $quantity,
        int $containerId,
        string $container,
        int $transactionId,
        int $locationId
    ) {
        $input = Input::create(
            [
                'supplier' => $supplier,
                'serial' => $serial,
                'item_id' => $itemId,
                'item_quantity' => $quantity,
                'type_consignment' => 'MY',
                'container_id' => $containerId,
                'transaction_type_id' => $transactionId,
                'location_id' => $locationId,
                'user_id' => Auth::id()
            ]
        );

        $cont = Container::where('id', '=', $containerId)->firstOrFail();

        $yh003 = YH003::query()->insert([
            'H3CONO' => $container ?? '',
            'H3DDTE' => Carbon::parse($cont->arrival_date)->format('Ymd') ?? '',
            'H3DTIM' => Carbon::parse($cont->arrival_time)->format('His') ?? '',
            'H3PROD' => $item,
            'H3SUCD' => $supplier,
            'H3SENO' => $serial,
            'H3RQTY' => $quantity,
            'H3CUSR' => Auth::user()->user_infor ?? '',
            'H3RDTE' => Carbon::parse($input->created_at)->format('Ymd'),
            'H3RTIM' => Carbon::parse($input->created_at)->format('His')
        ]);

        $itemInventory = Inventory::where([
            ['item_id', '=', $itemId],
            ['location_id', '=', $locationId]
        ])->first();

        $itemQuantity = $itemInventory->quantity ?? 0;

        $sum = 0;
        $sum = $quantity + $itemQuantity;

        Inventory::updateOrCreate(
            [
                'item_id' => $itemId,
                'location_id' => $locationId,
            ],
            [
                'quantity' => $sum
            ]
        );
    }

    /**
     *
     */
    public static function findExistingInput($supplier, $serial, $snp, $type, $no_order)
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6)->format('Ymd H:i:s.v');

        return Input::query()
            ->where([
                ['supplier', 'LIKE', $supplier],
                ['serial', 'LIKE', $serial],
                ['item_quantity', $snp],
                ['type_consignment', 'LIKE', $type],
                ['no_order', 'LIKE', $no_order]
            ])
            ->where('created_at', '>=', $sixMonthsAgo)
            ->first();
    }

    public static function materialReceived(String $supplier, String $serial, int $item_id, int $item_quantity, int $container_id, int $transaction_type_id, int $location_id, String $no_order): Input
    {
        $input = Input::create(
            [
                'supplier' => $supplier,
                'serial' => $serial,
                'item_id' => $item_id,
                'item_quantity' => $item_quantity,
                'container_id' => $container_id,
                'transaction_type_id' => $transaction_type_id,
                'location_id' => $location_id,
                'no_order' => $no_order
            ]
        );

        return $input;
    }

    public static function adjustInventoryInput(int $item_id, int $location, int $part_qty)
    {

        // Obtener o crear la entrada en Inventory
        $itemInventoryNew = Inventory::where([
            ['item_id', '=', $item_id],
            ['location_id', '=', $location]
        ])->first();

        $currentQuantity = $itemInventoryNew->quantity ?? 0;
        $newQuantity = $currentQuantity + $part_qty;

        // Actualizar la cantidad en Inventory
        Inventory::updateOrCreate(
            ['item_id' => $item_id, 'location_id' => $location],
            ['quantity' => $newQuantity]
        );
    }
}
