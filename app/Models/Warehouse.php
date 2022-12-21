<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'lid', 'code', 'name', 'description', 'status',
    ];

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
