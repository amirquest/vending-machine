<?php

namespace App\Models;

use Filament\Panel;
use Fouladgar\OTP\Concerns\HasOTPNotify;
use Fouladgar\OTP\Contracts\OTPNotifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\Contracts\HasApiTokens as ContractsHasApiTokens;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;

class Admin extends Authenticatable implements OTPNotifiable, ContractsHasApiTokens, FilamentUser
{
    use HasFactory;
    use Notifiable;
    use HasOTPNotify;
    use HasApiTokens;
    use SoftDeletes;
    use HasRoles;

    protected $fillable = [
        'password',
        'email',
        'name',
        'family',
        'mobile',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('isActive', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }

    /*
   |--------------------------------------------------------------------------
   | Accessors/Mutators
   |--------------------------------------------------------------------------
   */

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: function ($val, $attr) {
                if (! empty($this->name) && ! empty($this->family)) {
                    return $this->name.' '.$this->family;
                }

                return $this->mobile;
            }
        );
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
