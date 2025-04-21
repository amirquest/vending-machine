<?php

namespace App\Services\VendingMachine;

use App\Enums\OrderStatusEnum;
use App\Enums\VendingMachineStatusEnum;
use App\Models\Order;
use App\Models\VendingMachine;
use App\Repositories\OrderRepository;
use App\Repositories\VendingMachineRepository;
use App\Services\Order\OrderService;
use App\Services\Order\UpdateOrderStatusService;
use App\Services\Payment\Exceptions\PurchaseFailedException;
use App\Services\VendingMachine\Exceptions\VendingMachineInquiryFailedException;
use App\Services\VendingMachine\Gateways\VendingMachineGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\RequestException;
use Throwable;

class VendingMachineService
{
    public function __construct(
        private readonly VendingMachineRepository         $vendorMachineRepository,
        private readonly OrderService                     $orderService,
        private readonly VendingMachineGateway            $vendorMachineGateway,
        private readonly UpdateOrderStatusService         $updateOrderStatusService,
        private readonly UpdateVendingMachineStatusService $updateVendorMachineStatusService
    )
    {
    }

    public function findOneEligible(int $itemId): Model|VendingMachine
    {
        return $this->vendorMachineRepository->findAvailableWithItem($itemId);
    }

    /**
     * @throws VendingMachineInquiryFailedException
     * @throws Throwable
     */
    public function inquiryOrderStatus(Order $order): void
    {
        $vendorMachine = $order->vendingMachine;

        $status = $this->vendorMachineGateway->inquiryOrderStatus($vendorMachine->slug);

        if ($status === VendingMachineStatusEnum::IDLE()) {
            $this->vendorMachineRepository->transactional(function () use ($order, $vendorMachine) {
                $this->updateOrderStatusService->update($order, OrderStatusEnum::DELIVERED());

                $this->updateVendorMachineStatusService->update($vendorMachine, VendingMachineStatusEnum::IDLE());
            });

            return;
        }

        $this->vendorMachineRepository->transactional(function () use ($order, $vendorMachine) {
            $healthyRetryInterval = $this->calculateIntervalPenaltyCoefficient($vendorMachine);
            $this->updateVendorMachineStatusService->update(
                $vendorMachine,
                VendingMachineStatusEnum::UNHEALTHY(),
                [
                    'healthy_retry_interval' => $healthyRetryInterval,
                    'unhealthy_count' => $vendorMachine->unhealthy_count + 1,
                ]
            );

            $this->updateOrderStatusService->update($order, OrderStatusEnum::CHOOSING_VENDING_MACHINE());

            $this->orderService->buy($order);
        });
    }

    private function calculateIntervalPenaltyCoefficient(VendingMachine $vendorMachine): int
    {
        $statusLog = $vendorMachine->statusLogs()->latest()->first();

        $penaltyCoefficient = ($statusLog->from === VendingMachineStatusEnum::TEST())
            ? config('vending-machine.penalty_coefficient')
            : 1;

        return $vendorMachine->healthy_retry_interval * $penaltyCoefficient;
    }
}
