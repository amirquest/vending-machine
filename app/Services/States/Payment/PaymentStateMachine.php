<?php

namespace App\Services\States\Payment;

use App\Services\StateMachine\AbstractStateMachine;
use App\Services\StateMachine\Contracts\ShouldUpdateStatusChangedAtInterface;

class PaymentStateMachine extends AbstractStateMachine implements ShouldUpdateStatusChangedAtInterface
{

}
