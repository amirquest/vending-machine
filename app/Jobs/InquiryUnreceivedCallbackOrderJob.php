<?php

namespace App\Jobs;

use App\Repositories\OrderRepository;
use App\Services\VendingMachine\Exceptions\VendingMachineInquiryFailedException;
use App\Services\VendingMachine\VendingMachineService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class InquiryUnreceivedCallbackOrderJob implements ShouldQueue
{
    use Queueable;


    public function __construct(private readonly int $orderId)
    {
        //
    }


    /**
     * @throws VendingMachineInquiryFailedException
     */
    public function handle(
        OrderRepository      $orderRepository,
        VendingMachineService $vendorMachineService
    ): void
    {
        $order = $orderRepository->findUnreceivedCallbackOrder($this->orderId);

        $vendorMachineService->inquiryOrderStatus($order);
    }
}
