<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeasurementType extends Model
{
    use HasFactory;

    protected $dateFormat='Ymd H:i:s.v';

    protected $fillable = [
        'code', 'name', 'description', 'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
