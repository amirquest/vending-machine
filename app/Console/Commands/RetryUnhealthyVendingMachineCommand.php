<?php

namespace App\Console\Commands;

use App\Jobs\RetryUnhealthyVendingMachineJob;
use App\Models\VendingMachine;
use App\Repositories\VendingMachineRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RetryUnhealthyVendingMachineCommand extends Command
{
    protected $signature = 'vm:retry:unhealthy-vending-machine';

    protected $description = 'Retry unhealthy vending machine';

    public function handle(VendingMachineRepository $vendingMachineRepository): void
    {
        $vendingMachineRepository->getUnhealthyVendingMachines()
            ->each(function (VendingMachine $vendorMachine) {
                RetryUnhealthyVendingMachineJob::dispatch($vendorMachine->id);
            });

    }
}
