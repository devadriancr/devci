<?php

namespace App\Models;

use App\Models\output;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'user_infor'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     *
     */
    public function containers(): HasMany
    {
        return $this->hasMany(Container::class);
    }

    /**
     *
     */
    public function consignments(): HasMany
    {
        return $this->hasMany(ConsignmentInstruction::class);
    }

    /**
     *
     */
    public function shipping(): HasMany
    {
        return $this->hasMany(ShippingInstruction::class);
    }

    /**
     *
     */
    public function orderinformation(): BelongsTo
    {
        return $this->belongsTo(orderinformation::class);
    }

    /**
     *
     */
    public function inputs(): HasMany
    {
        return $this->hasMany(Input::class);
    }

    /**
     *
     */
    public function outputs(): HasMany
    {
        return $this->hasMany(output::class);
    }

    /**
     *
     */
    public function inputSupplier(): HasMany
    {
        return $this->hasMany(InputSupplier::class);
    }
}
