<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'tid', 'code', 'name', 'description', 'status',
    ];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(Output::class);
    }

    public function inputs(): HasMany
    {
        return $this->hasMany(Input::class);
    }
}
