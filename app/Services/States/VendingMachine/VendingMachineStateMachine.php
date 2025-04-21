<?php

namespace App\Services\States\VendingMachine;

use App\Services\StateMachine\AbstractStateMachine;
use App\Services\StateMachine\Contracts\ShouldUpdateStatusChangedAtInterface;
use App\Services\StateMachine\Exceptions\InvalidConfigurationException;
use App\Services\StateMachine\StateTransition;

class VendingMachineStateMachine extends AbstractStateMachine implements ShouldUpdateStatusChangedAtInterface
{
    /**
     * @throws InvalidConfigurationException
     */
    public static function transitions(): StateTransition
    {
        return parent::transitions()
            ->default(Idle::class)
            ->allowTransition(Idle::class, Preparing::class)
            ->allowTransition(Preparing::class, [
                Idle::class,
                Unhealthy::class
            ])
            ->allowTransition(Unhealthy::class, Test::class)
            ->allowTransition(Test::class, [
                Unhealthy::class,
                Idle::class
            ]);

    }
}
