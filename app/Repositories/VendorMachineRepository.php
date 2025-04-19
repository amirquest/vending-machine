<?php

namespace App\Repositories;

use App\Enums\VendorMachineStatusEnum;
use App\Models\VendorMachine;
use App\Repositories\Contracts\AbstractRepository;

class VendorMachineRepository extends AbstractRepository
{
    public function findAvailableWithItem(int $itemId)
    {
        //TODO: handle sort by out_of_service count
        return $this->getQuery()
            ->whereIn('status', [
                VendorMachineStatusEnum::IDLE(),
                VendorMachineStatusEnum::TEST(),
                ])
            ->whereHas('inventory', function ($query) use ($itemId) {
                $query->where('item_id', $itemId)
                ->where('quantity', '>', 0);
            })
            ->first();
    }

    protected function instance(array $attributes = []): VendorMachine
    {
        return new VendorMachine($attributes);
    }
}
