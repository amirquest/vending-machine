<?php

namespace App\Repositories;

use App\Models\VendorMachine;
use App\Repositories\Contracts\AbstractRepository;

class VendorMachineRepository extends AbstractRepository
{
    protected function instance(array $attributes = []): VendorMachine
    {
        return new VendorMachine($attributes);
    }
}
