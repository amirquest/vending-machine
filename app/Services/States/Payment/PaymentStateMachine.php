<?php

namespace App\Services\States\Payment;

use App\Services\StateMachine\AbstractStateMachine;
use App\Services\StateMachine\Contracts\ShouldUpdateStatusChangedAtInterface;
use App\Services\StateMachine\Exceptions\InvalidConfigurationException;
use App\Services\StateMachine\StateTransition;

class PaymentStateMachine extends AbstractStateMachine implements ShouldUpdateStatusChangedAtInterface
{
    /**
     * @throws InvalidConfigurationException
     */
    public static function transitions(): StateTransition
    {
        return parent::transitions()
            ->default(Paid::class)
            ->allowTransition(Paid::class, Returned::class);
    }
}
