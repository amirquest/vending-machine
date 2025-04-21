<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Services\StateMachine\Concerns\HasState;
use App\Services\States\Order\OrderStateMachine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
    use HasState;

    public static string $stateMachine = OrderStateMachine::class;

    protected $fillable = [
        'payment_id',
        'item_id',
        'status_changed_at',
        'vending_machine_id',
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

        static::creating(function (Model $model) {
           $model->status = OrderStatusEnum::INIT();
        });

        static::created(function (Model $model) {
            $model->identifier = identifier($model->id);
            $model->save();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'identifier';
    }

    public function vendingMachine(): HasOne
    {
        return $this->hasOne(VendingMachine::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function item(): HasOne
    {
        return $this->hasOne(Item::class);
    }
}
