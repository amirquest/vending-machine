<?php

namespace App\Services\Order;

use App\Enums\VendorMachineStatusEnum;
use App\Jobs\SendOrderToVendorMachineJob;
use App\Models\Order;
use App\Repositories\InventoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Services\Inventory\Exceptions\InsufficientItemInInventoryException;
use App\Services\Inventory\InventoryService;
use App\Services\VendorMachine\UpdateVendorMachineStatusService;
use App\Services\VendorMachine\VendorMachineService;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class OrderService
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly OrderRepository $orderRepository,
        private readonly VendorMachineService $vendorMachineService,
        private readonly InventoryRepository $inventoryRepository,
        private readonly UpdateVendorMachineStatusService $updateVendorMachineStatusService,
        private readonly InventoryService $inventoryService
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

        $attributes = ['payment_id' => $payment->id,  'item_id' => $itemId,];
        $order = $this->orderRepository->persist($attributes);

        SendOrderToVendorMachineJob::dispatch($order->id);

        return $order;
    }

    /**
     * @throws Throwable
     */
    public function buy(Order $order): Model|Order
    {
        $vendorMachine = $this->vendorMachineService->findOneEligible($order->item_id);

        $this->inventoryRepository->transactional(function () use ($vendorMachine, $itemId) {
            $this->updateVendorMachineStatusService->update($vendorMachine, VendorMachineStatusEnum::PREPARING());

            $inventory = $this->inventoryRepository->findByVendorMachineAndItem($vendorMachine->id, $itemId);

            $this->inventoryRepository->decrement($inventory, 'quantity');

            // send request
        });
    }
}
