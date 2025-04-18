<?php

namespace App\Services\StateMachine\Contracts;

interface StateTaggableInterface
{
    public function stateTag(): array;
}
