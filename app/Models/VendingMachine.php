<?php

namespace App\Models;

use App\Services\StateMachine\Concerns\HasState;
use App\Services\States\VendingMachine\VendingMachineStateMachine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class VendingMachine extends Model
{
    use HasFactory;
    use HasState;

    public static string $stateMachine = VendingMachineStateMachine::class;

    protected $fillable = [
        'slug',
        'name',
        'status_changed_at',
        'healthy_retry_interval',
        'unhealthy_count',
    ];

    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function statusLogs(): MorphMany
    {
        return $this->morphMany(StatusLog::class, 'subject');
    }

    public function getHealthyRetryIntervalAttribute()
    {
        return $this->healthy_retry_interval ?? config('vending-machine.default_healthy_retry_interval');
    }
}
