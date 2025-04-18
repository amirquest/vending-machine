<?php

namespace App\Services\StateMachine;

use App\Services\StateMachine\Contracts\StateMachineInterface;
use App\Services\StateMachine\Contracts\StateTaggableInterface;
use App\Services\StateMachine\Exceptions\StateNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class StateResolver
{
    protected Model $model;

    /**
     * @throws StateNotFoundException
     */
    public function make(Model $model): StateMachineInterface|StateTaggableInterface
    {
        $this->model = $model;

        $stateClass = $this->resolveStateClass($model->status);

        return new $stateClass($model);
    }

    /**
     * @throws StateNotFoundException
     */
    private function resolveStateClass(string $state): string
    {
        $stateClass = $this->stateMapping()->get($state);

        if (!is_string($stateClass) || !class_exists($stateClass)) {
            throw StateNotFoundException::make($state);
        }

        return $stateClass;
    }

    private function stateMapping(): Collection
    {
        return $this->model::getStateTransition()->getStateMapping();
    }
}
