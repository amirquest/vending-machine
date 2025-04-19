<?php

namespace App\Jobs;

use App\Repositories\OrderRepository;
use App\Services\Order\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendOrderToVendorMachineJob implements ShouldQueue
{
    use Queueable;


    public function __construct(private readonly int $orderId)
    {

    }


    public function handle(OrderRepository $orderRepository, OrderService $orderService): void
    {
        $order = $orderRepository->findOrFail($this->orderId);

        $orderService->buy($order);
    }
}
