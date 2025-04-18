<?php

namespace App\Services\StateMachine;

use App\Models\StatusLog;
use App\Services\StateMachine\AbstractStateMachine as State;

abstract class Transition
{
    private ?statusLog $statusLog = null;

    public function newState(): State
    {
        return $this->newState;
    }

    public function setStatusLog(StatusLog $statusLog): self
    {
        $this->statusLog = $statusLog;

        return $this;
    }

    public function getStatusLog(): ?statusLog
    {
        return $this->statusLog;
    }
}
