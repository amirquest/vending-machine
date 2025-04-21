<?php

namespace App\Services\StateMachine\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface StateMachineInterface
{
    public function transitionTo($newState, ...$transitionArgs): Model;

    public function canTransitionTo($newState, ...$transitionArgs): bool;

    public function currentState(): string;

    public static function getStates(): Collection;

    public function transitionableStates(): array;
}
