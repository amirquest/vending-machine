<?php

namespace App\Services\StateMachine\Exceptions;

class NotAllowedTransitionException extends StateMachineException
{
    public static function make(string $transitionClass, string $modelClass): static
    {
        return new static("The transition `$transitionClass` is not allowed on model `$modelClass` at the moment.");
    }
}
