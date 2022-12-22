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
        'user_id'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transactiontype::class, 'transaction_type_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
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
    public  static function storeInput(int $item, string $quantity, int $transaction, int $location)
    {
        Input::create(
            [
                'item_id' => $item,
                'item_quantity' => $quantity,
                'transaction_type_id' => $transaction,
                'location_id' => $location,
                'user_id' => Auth::id()
            ]
        );
    }

    /**
     *
     */
    public static function storeInputConsignment(string $supplier, string $serial, int $item, int $quantity, int $container, int $transaction, int $location)
    {
        $info_cont = Container::where('id', '=', $container)->firstOrFail();
        $part_no = Item::where('id', '=', $item)->firstOrFail();

        $input = Input::create(
            [
                'serial' => $serial,
                'supplier' => $supplier,
                'item_id' => $item,
                'item_quantity' => $quantity,
                'container_id' => $container,
                'transaction_type_id' => $transaction,
                'location_id' => $location,
                'user_id' => Auth::id()
            ]
        );

        YH003::query()->insert([
            'H3CONO' => $info_cont->code ?? '',
            'H3DDTE' => Carbon::parse($info_cont->arrival_date)->format('Ymd') ?? '',
            'H3DTIM' => Carbon::parse($info_cont->arrival_time)->format('His') ?? '',
            'H3PROD' => $part_no->item_number,
            'H3SUCD' => $supplier,
            'H3SENO' => $serial,
            'H3RQTY' => $quantity,
            'H3CUSR' => Auth::user()->user_infor ?? '',
            'H3RDTE' => Carbon::parse($input->created_at)->format('Ymd'),
            'H3RTIM' => Carbon::parse($input->created_at)->format('His')
        ]);

        $itemInventory = Inventory::where([
            ['item_id', '=', $item],
            ['location_id', '=', $location]
        ])->first();

        $itemQuantity = $itemInventory->quantity ?? 0;

        $sum = 0;
        $sum = $quantity + $itemQuantity;

        Inventory::updateOrCreate(
            [
                'item_id' => $item,
                'location_id' => $location,
            ],
            [
                'quantity' => $sum
            ]
        );
    }
}
