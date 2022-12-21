<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StandardPack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'quantity', 'standard_type_id', 'status',
    ];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }

    public function standardType(): BelongsTo
    {
        return $this->belongsTo(StandardType::class);
    }
}
