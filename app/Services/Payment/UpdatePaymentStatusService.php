<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Services\StateMachine\StateMachineService;
use Illuminate\Database\Eloquent\Model;

class UpdatePaymentStatusService
{
    public function __construct(private readonly StateMachineService $stateMachine)
    {
    }

    public function update(Payment $payment, string $targetStatus, ...$transitionArgs): Model|Payment
    {
        return $this->stateMachine->subject($payment)->transition($targetStatus, ...$transitionArgs);
    }
}
