<?php

namespace App\Jobs;

use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\Order\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class SendOrderToVendingMachineJob implements ShouldQueue
{
    use Queueable;


    public function __construct(private readonly int $orderId)
    {

    }


    /**
     * @throws Throwable
     */
    public function handle(OrderRepository $orderRepository, OrderService $orderService): void
    {
        /** @var Order $order */
        $order = $orderRepository->findOrFail($this->orderId);

        $orderService->buy($order);
    }
}
