<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsignmentInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial', 'container_id',
    ];

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }
}
