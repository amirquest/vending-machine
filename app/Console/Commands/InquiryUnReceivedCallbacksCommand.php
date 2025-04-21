<?php

namespace App\Console\Commands;

use App\Jobs\InquiryUnreceivedCallbackOrderJob;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Console\Command;

class InquiryUnReceivedCallbacksCommand extends Command
{
    protected $signature = 'vm:inquiry:unreceived-callbacks';

    protected $description = 'Inquiry UnReceived Callbacks';

    public function handle(OrderRepository $orderRepository): void
    {

        $orderRepository->getEligibleOrdersWithUnreceivedCallback()
            ->cursor()
            ->each(function (Order $order) {
                InquiryUnreceivedCallbackOrderJob::dispatch($order->id);
            });
    }
}
