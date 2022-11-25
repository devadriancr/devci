<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'tid', 'code', 'name', 'description', 'status',
    ];
// relacion 1:n de outputs
public function output():HasMany
{
    return $this->hasMany(Output::class);
}
// relacion 1:n de intputs
public function intput():HasMany
{
    return $this->hasMany(Input::class);
}

}
