<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use App\Services\StateMachine\Concerns\HasState;
use App\Services\States\Payment\PaymentStateMachine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;
    use HasState;

    public static string $stateMachine = PaymentStateMachine::class;

    protected $fillable = [
        'amount',
        'status_changed_at',
        'request_code',
    ];

    public static function booted(): void
    {
        parent::booted();

        static::creating(function (Model $model) {
            $model->request_code = Str::ulid()->toString();
            $model->status = PaymentStatusEnum::PAID();
        });
    }
}
