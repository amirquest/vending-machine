<?php

namespace App\Services\StateMachine\Concerns;

use App\Models\StatusLog;
use App\Services\StateMachine\Contracts\StateMachineInterface;
use App\Services\StateMachine\StateTransition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasState
{
    public static function bootHasState(): void
    {
        self::creating(function (Model $model) {
            $model->setStateDefault();
        });
    }

    public static function getStateTransition(): ?StateTransition
    {
        $state = self::$stateMachine;

        if (!is_subclass_of($state, StateMachineInterface::class)) {
            return null;
        }

        return $state::transitions();
    }

    public function statusLogs(): MorphMany
    {
        return $this->morphMany(StatusLog::class, 'subject');
    }

    public function initializeHasState(): void
    {
        $this->setStateDefault();
    }

    private function setStateDefault(): void
    {
        if ($this->status !== null) {
            return;
        }

        $stateTransition = static::getStateTransition();
        if ($stateTransition->defaultStateClass === null) {
            return;
        }

        $this->status = $stateTransition->defaultStateClass::getMorphClass();
    }
}
