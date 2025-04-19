<?php

namespace App\Repositories;

use App\Models\Inventory;
use App\Repositories\Contracts\AbstractRepository;

class InventoryRepository extends AbstractRepository
{

    public function findByVendorMachineAndItem(int $vendorMachineId, int $itemId): Inventory
    {
        return $this->getQuery()
            ->where('vendor_machine_id', $vendorMachineId)
            ->where('item_id', $itemId)
            ->lockForUpdate()
            ->firstOrFail();
    }

    public function hasInventorySpecificItem(int $itemId): bool
    {
        return $this->getQuery()
            ->where('item_id', $itemId)
            ->where('quantity', '>', 0)
            ->exists();
    }

    protected function instance(array $attributes = []): Inventory
    {
        return new Inventory($attributes);
    }
}
