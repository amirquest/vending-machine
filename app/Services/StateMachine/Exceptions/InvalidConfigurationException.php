<?php

namespace App\Services\StateMachine\Exceptions;

use App\Services\StateMachine\Transition;

class InvalidConfigurationException extends StateMachineException
{
    public static function whenTransitionDoesNotExtendBaseClass(string $transitionClass): static
    {
        $baseClass = Transition::class;

        return new static("Transition `$transitionClass` does not extend the `$baseClass` base class.");
    }

    public static function whenClassDoesNotExtendBaseClass(string $class, string $baseStateClass): static
    {
        return new static("Class `$class` does not extend the `$baseStateClass` base class.");
    }

    public static function whenClassDoesNotImplementBaseInterface(object $state, string $interfaceClass): static
    {
        return new static(
            sprintf('The %s must be an instance of %s', get_class($state), $interfaceClass)
        );
    }
}
