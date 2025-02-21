<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ConsignmentInstruction extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'supplier', 'serial', 'part_qty', 'part_no', 'location', 'flag', 'container_id', 'user_id'
    ];

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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     */
    public static function storeConsignment(string $serial, string $supplier, int $part_qty, string $part_no, string $location, int $containerId)
    {
        $item = Item::where('item_number', 'LIKE', '%' . $part_no . '%')->firstOrFail();
        $cont = Container::where('id', $containerId)->firstOrFail();
        $tran = TransactionType::where('code', '=', 'U3')->firstOrFail();
        $loca = Location::where('code', 'LIKE', $location . '%')->firstOrFail();

        ConsignmentInstruction::create(
            [
                'supplier' => $supplier,
                'serial' => $serial,
                'part_qty' => $part_qty,
                'part_no' => $part_no,
                'location' => $location,
                'flag' => true,
                'container_id' => $containerId,
                'user_id' => Auth::id(),
            ]
        );

        Input::storeInputConsignment(
            $supplier,
            $serial,
            $item->id,
            $item->item_number,
            $part_qty,
            $cont->id,
            $cont->code,
            $tran->id,
            $loca->id
        );
    }
}
