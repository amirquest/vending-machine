<?php

namespace App\Models;

use App\Services\StateMachine\Concerns\HasState;
use App\Services\States\VendorMachine\VendorMachineStateMachine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorMachine extends Model
{
    use HasFactory;
    use HasState;

    public static string $stateMachine = VendorMachineStateMachine::class;

    protected $fillable = [
        'slug',
        'name',
    ];

    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
