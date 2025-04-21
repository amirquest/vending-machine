<?php

namespace App\Services\Order;

use App\Enums\OrderStatusEnum;
use App\Enums\VendingMachineStatusEnum;
use App\Jobs\SendOrderToVendingMachineJob;
use App\Models\Order;
use App\Repositories\InventoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Services\Inventory\Exceptions\InsufficientItemInInventoryException;
use App\Services\Inventory\InventoryService;
use App\Services\VendingMachine\Gateways\VendingMachineGateway;
use App\Services\VendingMachine\UpdateVendingMachineStatusService;
use App\Services\VendingMachine\VendingMachineService;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class OrderService
{
    public function __construct(
        private readonly PaymentRepository                 $paymentRepository,
        private readonly OrderRepository                   $orderRepository,
        private readonly InventoryRepository               $inventoryRepository,
        private readonly VendingMachineService             $vendorMachineService,
        private readonly InventoryService                  $inventoryService,
        private readonly VendingMachineGateway             $vendingMachineGateway,
        private readonly UpdateOrderStatusService          $updateOrderStatusService,
        private readonly UpdateVendingMachineStatusService $updateVendorMachineStatusService,
        private readonly Dispatcher                        $dispatcher
    )
    {
    }

    /**
     * @throws InsufficientItemInInventoryException
     */
    public function initOrder(int $itemId, string $requestCode): Model|Order
    {
        $payment = $this->paymentRepository->findByRequestCode($requestCode);

        $this->inventoryService->checkInventoryHasItem($itemId);

        $attributes = ['payment_id' => $payment->id, 'item_id' => $itemId];
        $order = $this->orderRepository->persist($attributes);

        $this->dispatcher->dispatch(new SendOrderToVendingMachineJob($order->id));

        return $order;
    }

    /**
     * @throws Throwable
     */
    public function buy(Order $order): void
    {
        $itemId = $order->item_id;

        try {
            $vendingMachine = $this->vendorMachineService->findOneEligible($itemId);
        } catch (ModelNotFoundException $e) {
            if (
                $this->updateOrderStatusService->isNotSameStatusForUpdate(
                    $order,
                    OrderStatusEnum::CHOOSING_VENDING_MACHINE()
                )
            ) {
                $this->updateOrderStatusService->update($order, OrderStatusEnum::CHOOSING_VENDING_MACHINE());
            }

            return;
        }

        $this->inventoryRepository->transactional(function () use ($order, $vendingMachine, $itemId) {
            $this->updateVendorMachineStatusService->update($vendingMachine, VendingMachineStatusEnum::PREPARING());

            $inventory = $this->inventoryRepository->findByVendorMachineAndItem($vendingMachine->id, $itemId);

            $this->inventoryRepository->decrement($inventory, 'quantity');

            $this->vendingMachineGateway->orderItem($vendingMachine->slug, $order->item->slug);

            $this->updateOrderStatusService->update(
                $order,
                OrderStatusEnum::SUBMITTED(),
                ['vending_machine_id' => $vendingMachine->id]
            );
        });
    }

    /** @throws Throwable */
    public function callback(int $identifier): void
    {
        $order = $this->orderRepository->findByIdentifier($identifier);

        $this->orderRepository->transactional(function () use ($order) {
            $this->updateOrderStatusService->update($order, OrderStatusEnum::DELIVERED());

            $this->updateVendorMachineStatusService->update($order->vendorMachine, VendingMachineStatusEnum::IDLE());
        });
    }
}
