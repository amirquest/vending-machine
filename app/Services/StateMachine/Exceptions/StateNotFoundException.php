<?php

namespace App\Services\StateMachine\Exceptions;

class StateNotFoundException extends StateMachineException
{
    public static function make(string $state): static
    {
        return new static("The state '$state' was not found.");
    }
}
