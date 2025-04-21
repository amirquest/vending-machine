<?php

namespace App\Repositories;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Repositories\Contracts\AbstractRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderRepository extends AbstractRepository
{
    public function findByIdentifier(int $identifier): Order
    {
        return $this->getQuery()
            ->where('identifier', $identifier)
            ->firstOrFail();
    }

    public function getEligibleOrdersWithUnreceivedCallback(?int $orderId = null): Builder
    {
        return $this->getQuery()
            ->where('status', OrderStatusEnum::SUBMITTED())
            ->where('status_changed_at', '>', Carbon::now()->subMinute())
            ->with(['vendingMachine'])
            ->when($orderId, function ($query) use ($orderId) {
                return $query->where('id', $orderId);
            })
            ->select(['id']);
    }

    public function findUnreceivedCallbackOrder(int $orderId): Model|Order
    {
        return $this->getEligibleOrdersWithUnreceivedCallback($orderId)->firstOrFail();
    }

    public function getEligibleWaitingOrders(?int $orderId = null): Builder
    {
        $interval = config('vending-machine.preparation_time');

        return $this->getQuery()
            ->whereIn('status', [
                OrderStatusEnum::CHOOSING_VENDING_MACHINE(),
                OrderStatusEnum::INIT(),
            ])
            ->where('status_changed_at', '>', Carbon::now()->subMinutes($interval))
            ->when($orderId, function ($query) use ($orderId) {
                return $query->where('id', $orderId);
            })
            ->select(['id']);
    }

    public function findWaitingOrder(int $orderId): Model|Order
    {
        return $this->getEligibleWaitingOrders($orderId)->firstOrFail();
    }

    protected function instance(array $attributes = []): Order
    {
        return new Order($attributes);
    }
}
