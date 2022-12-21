<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StandardType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'status',
    ];

    public function standardPacks(): HasMany
    {
        return $this->hasMany(StandardPack::class);
    }
    
    public function fromDateTime($value)

    {

        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');

    }
}
