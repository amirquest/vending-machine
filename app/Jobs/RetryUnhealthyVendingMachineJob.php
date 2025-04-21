<?php

namespace App\Jobs;

use App\Enums\VendingMachineStatusEnum;
use App\Models\VendingMachine;
use App\Repositories\VendingMachineRepository;
use App\Services\VendingMachine\UpdateVendingMachineStatusService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RetryUnhealthyVendingMachineJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly int $vendorMachineId)
    {
    }


    public function handle(
        VendingMachineRepository         $vendorMachineRepository,
        UpdateVendingMachineStatusService $updateVendorMachineStatusService
    ): void
    {
        /** @var VendingMachine $vendorMachine */
        $vendorMachine = $vendorMachineRepository->findUnhealthyById($this->vendorMachineId);

        if ($vendorMachine->status_changed_at < Carbon::now()->subMinutes($vendorMachine->healthy_retry_interval)) {
            return;
        }

        $updateVendorMachineStatusService->update($vendorMachine, VendingMachineStatusEnum::TEST());
    }
}
