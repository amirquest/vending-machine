<?php

namespace App\Services\States\Order;

use App\Services\StateMachine\AbstractStateMachine;
use App\Services\StateMachine\Contracts\ShouldUpdateStatusChangedAtInterface;

class OrderStateMachine extends AbstractStateMachine implements ShouldUpdateStatusChangedAtInterface
{

}
