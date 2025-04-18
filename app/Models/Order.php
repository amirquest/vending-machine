<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Services\StateMachine\Concerns\HasState;
use App\Services\StateMachine\StateMachineService;
use App\Services\States\Order\OrderStateMachine;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
    use HasState;

    public static string $stateMachine = OrderStateMachine::class;

    protected $fillable = [
        'customer_id',
        'package_id',
        'has_account_number',
        'status_changed_at',
        'status',
        'cancellation_reason',
        'reject_reason',
    ];

    protected $hidden = [
        'id',
    ];

    protected function casts(): array
    {
        return [
            'has_account_number' => 'boolean',
            'status_changed_at' => 'datetime'
        ];
    }

    public static function booted(): void
    {
        parent::booted();

        static::created(function (Model $model) {
            $model->identifier = identifier($model->id);
            $model->save();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'identifier';
    }
}
