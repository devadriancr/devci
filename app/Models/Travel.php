<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasOne;

class travel extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dateFormat = 'Ymd H:i:s.v';

    // relacion 1:n de outputs
    public function output(): HasMany
    {
        return $this->hasMany(Output::class);
    }

    // relacion 1:n de intputs
    public function intput(): HasMany
    {
        return $this->hasMany(Input::class);
    }

    // relacion 1:n de orders
    public function orderinformation(): hasOne
    {
        return $this->hasOne(orderinformation::class);
    }

    // relacion de 1:N inversa location
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     *
     */
    public function inputSupplier(): HasMany
    {
        return $this->hasMany(InputSupplier::class);
    }
}
