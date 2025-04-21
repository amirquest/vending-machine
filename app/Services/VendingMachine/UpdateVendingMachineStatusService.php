<?php

namespace App\Services\VendingMachine;

use App\Models\VendingMachine;
use App\Services\StateMachine\StateMachineService;
use Illuminate\Database\Eloquent\Model;

class UpdateVendingMachineStatusService
{
    public function __construct(private readonly StateMachineService $stateMachine) {
    }

    public function update(VendingMachine $vendorMachine, string $targetStatus, ...$transitionArgs): Model|VendingMachine
    {
        return $this->stateMachine->subject($vendorMachine)->transition($targetStatus, ...$transitionArgs);
    }

    public function canUpdateStatus(string $targetStatus, VendingMachine $vendorMachine): bool
    {
        return $this->stateMachine->canTransitionTo($targetStatus, $vendorMachine);
    }

    public function isNotSameStatusForUpdate(VendingMachine $vendorMachine, string $targetStatus): bool
    {
        return $vendorMachine->status !== $targetStatus;
    }

}
