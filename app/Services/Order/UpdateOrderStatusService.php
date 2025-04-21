<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Services\StateMachine\StateMachineService;
use Illuminate\Database\Eloquent\Model;

class UpdateOrderStatusService
{
    public function __construct(private readonly StateMachineService $stateMachine)
    {
    }

    public function update(Order $order, string $targetStatus, ...$transitionArgs): Model|Order
    {
        return $this->stateMachine->subject($order)->transition($targetStatus, ...$transitionArgs);
    }

    public function isNotSameStatusForUpdate(Order $order, string $targetStatus): bool
    {
        return $order->status !== $targetStatus;
    }

}
