<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TransactionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description'
    ];

    public function consignments(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\ConsignmentInstruction');
    }
}
