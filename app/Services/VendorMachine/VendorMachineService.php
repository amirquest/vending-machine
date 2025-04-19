<?php

namespace App\Services\VendorMachine;

use App\Repositories\VendorMachineRepository;

class VendorMachineService
{
    public function __construct(
        private readonly VendorMachineRepository $vendorMachineRepository,
    )
    {
    }

    public function findOneEligible(int $itemId)
    {
        return $this->vendorMachineRepository->findAvailableWithItem($itemId);
    }
}
