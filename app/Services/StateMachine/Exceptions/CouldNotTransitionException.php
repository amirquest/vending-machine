<?php

namespace App\Services\StateMachine\Exceptions;

class CouldNotTransitionException extends StateMachineException
{
    public static function make(string $from, string $to, string $modelClass): static
    {
        return new static("Transition from `{$from}` to `{$to}` on model `{$modelClass}` was not found.");
    }
}
