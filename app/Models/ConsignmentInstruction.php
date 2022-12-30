<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsignmentInstruction extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'supplier', 'serial', 'part_no', 'part_qty', 'flag', 'location', 'container_id', 'user_id'
    ];

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
