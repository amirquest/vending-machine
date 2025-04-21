<?php

namespace App\Repositories;

use App\Enums\VendingMachineStatusEnum;
use App\Models\VendingMachine;
use App\Repositories\Contracts\AbstractRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\LazyCollection;
use Random\RandomException;

class VendingMachineRepository extends AbstractRepository
{
    /** @throws RandomException  */
    public function findAvailableWithItem(int $itemId): Model|VendingMachine
    {
        $orderBY = random_int(0, 1) ? 'asc' : 'desc';

        return $this->getQuery()
            ->whereIn('status', [
                VendingMachineStatusEnum::IDLE(),
                VendingMachineStatusEnum::TEST(),
                ])
            ->whereHas('inventory', function ($query) use ($itemId) {
                $query->where('item_id', $itemId)
                ->where('quantity', '>', 0);
            })
            ->orderBy('unhealthy_count', $orderBY)
            ->firstOrFail();
    }

    public function getUnhealthyVendingMachines(): LazyCollection
    {
        return $this->getQuery()
            ->where('status', VendingMachineStatusEnum::UNHEALTHY())
            ->cursor();
    }

    public function findUnhealthyById(int $vendorMachineId): Model|VendingMachine
    {
        return $this->getQuery()
            ->where('status', VendingMachineStatusEnum::UNHEALTHY())
            ->findOrFail($vendorMachineId);
    }

    protected function instance(array $attributes = []): VendingMachine
    {
        return new VendingMachine($attributes);
    }
}
