<?php

namespace App\Jobs;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Repositories\OrderRepository;
use App\Services\Order\UpdateOrderStatusService;
use App\Services\Payment\UpdatePaymentStatusService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class CheckWaitingOrderJob implements ShouldQueue
{
    use Queueable;


    public function __construct(private readonly int $orderId)
    {
    }


    /**
     * @throws Throwable
     */
    public function handle(
        OrderRepository            $orderRepository,
        UpdateOrderStatusService   $updateOrderStatusService,
        UpdatePaymentStatusService $updatePaymentStatusService
    ): void
    {
        $order = $orderRepository->findWaitingOrder($this->orderId);

        $orderRepository->transactional(function () use ($order, $updateOrderStatusService, $updatePaymentStatusService) {
            $updateOrderStatusService->update($order, OrderStatusEnum::FAILED());

            $updatePaymentStatusService->update($order->payment, PaymentStatusEnum::RETURNED());
        });
    }
}
