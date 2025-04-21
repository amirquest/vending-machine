<?php

namespace App\Services\States\Order;

use App\Services\StateMachine\AbstractStateMachine;
use App\Services\StateMachine\Contracts\ShouldUpdateStatusChangedAtInterface;
use App\Services\StateMachine\Exceptions\InvalidConfigurationException;
use App\Services\StateMachine\StateTransition;
use App\Services\States\Order\Transitions\ToSubmittedTransition;

class OrderStateMachine extends AbstractStateMachine implements ShouldUpdateStatusChangedAtInterface
{
    /**
     * @throws InvalidConfigurationException
     */
    public static function transitions(): StateTransition
    {
        return parent::transitions()
            ->default(Init::class)
            ->allowTransition(Init::class, ChoosingVendingMachine::class)
            ->allowTransition(Submitted::class, [
                Delivered::class,
                ChoosingVendingMachine::class,
            ])
            ->allowTransition(ChoosingVendingMachine::class, Failed::class)
            ->allowTransition([
                Init::class,
                ChoosingVendingMachine::class
            ], Submitted::class, ToSubmittedTransition::class);
    }
}
