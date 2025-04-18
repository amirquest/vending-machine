<?php

namespace App\Services\VendorMachine;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\Order\Exceptions\OrderCancelStatusInvalidException;
use App\Services\Order\Exceptions\OrderRejectStatusInvalidException;
use App\Services\StateMachine\StateMachineService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Translation\Translator;
use Throwable;

class UpdateVendorMachineStatusService
{
    public function __construct(
        private readonly StateMachineService $stateMachine,
        private readonly Translator          $translator,
        private readonly OrderRepository      $orderRepository,
    ) {
    }

    public function update(Order $order, string $targetStatus, ...$transitionArgs): Model|Order
    {
        return $this->stateMachine->subject($order)->transition($targetStatus, ...$transitionArgs);
    }

    /**
     * @throws OrderCancelStatusInvalidException
     * @throws Throwable
     */
    public function cancelByCustomer(Order $order, mixed $transitionArgs): Model|Order
    {
        if (!$this->canUpdateStatus(OrderStatusEnum::CANCELED(), $order)) {
            throw new OrderCancelStatusInvalidException(
                $this->translator->get('exception.order_cancel_status_invalid')
            );
        }

        return $this->update($order, OrderStatusEnum::CANCELED(), $transitionArgs);
    }

    public function canUpdateStatus(string $targetStatus, Order $order): bool
    {
        return $this->stateMachine->canTransitionTo($targetStatus, $order);
    }

    /**
     * @throws OrderRejectStatusInvalidException
     * @throws Throwable
     */
    public function rejectByAdmin(Order $order, mixed $transitionArgs): Model|Order
    {
        if (!$this->canUpdateStatus(OrderStatusEnum::REJECTED(), $order)) {
            throw new OrderRejectStatusInvalidException(
                $this->translator->get('exception.order_reject_status_invalid')
            );
        }

        return $this->update($order, OrderStatusEnum::REJECTED(), $transitionArgs);
    }

    public function cancelByAdmin(int $orderId, ...$transitionArgs): void
    {
        $order = $this->orderRepository->findOrFail($orderId);

        $this->update($order, OrderStatusEnum::CANCELED(), $transitionArgs);
    }

    public function isNotSameStatusForUpdate(Order $order, string $targetStatus): bool
    {
        return $order->status !== $targetStatus;
    }

}
