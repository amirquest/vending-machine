<?php

namespace App\Console\Commands;

use App\Jobs\CheckWaitingOrderJob;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Console\Command;

class CheckWaitingOrdersCommand extends Command
{
    protected $signature = 'vm:check:waiting-orders';

    protected $description = 'Check orders with choosing vendor machine status';

    public function handle(OrderRepository $orderRepository): void
    {
        $orderRepository->getEligibleWaitingOrders()
            ->cursor()
            ->each(function (Order $order) {
                CheckWaitingOrderJob::dispatch($order->id);
            });
    }
}
