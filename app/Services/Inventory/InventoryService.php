<?php

namespace App\Services\Inventory;

use App\Repositories\InventoryRepository;
use App\Services\Inventory\Exceptions\InsufficientItemInInventoryException;

class InventoryService
{
    public function __construct(private readonly InventoryRepository $inventoryRepository)
    {
    }

    /**
     * @throws InsufficientItemInInventoryException
     */
    public function checkInventoryHasItem(int $itemId): bool
    {
        if (!$this->inventoryRepository->hasInventorySpecificItem($itemId)){
            throw new InsufficientItemInInventoryException();
        }
    }
}
