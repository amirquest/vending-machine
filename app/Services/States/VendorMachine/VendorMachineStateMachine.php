<?php

namespace App\Services\States\VendorMachine;

use App\Services\StateMachine\AbstractStateMachine;
use App\Services\StateMachine\Contracts\ShouldUpdateStatusChangedAtInterface;

class VendorMachineStateMachine extends AbstractStateMachine implements ShouldUpdateStatusChangedAtInterface
{

}
