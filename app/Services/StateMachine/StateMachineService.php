<?php

namespace App\Services\StateMachine;

use App\Services\StateMachine\Contracts\StateTaggableInterface as StateTaggable;
use App\Services\StateMachine\Exceptions\InvalidConfigurationException;
use App\Services\StateMachine\Exceptions\StateNotFoundException;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class StateMachineService
{
    private Model $subject;

    public function __construct(protected StateResolver $stateResolver)
    {
    }

    public function subject(Model $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function transitionFlow(string $flow): static
    {
        $this->subject::$stateMachine::setFlow($flow);

        return $this;
    }

    public function transition($newState, ...$transitionArgs): Model
    {
        $state = $this->stateResolver->make($this->subject);

        return $state->transitionTo($state::resolveStateClass($newState), ...$transitionArgs);
    }

    public function canTransitionTo($newState, Model $model, ...$transitionArgs): bool
    {
        $state = $this->stateResolver->make($model);

        return $state->canTransitionTo($state::resolveStateClass($newState), ...$transitionArgs);
    }

    /**
     * @throws StateNotFoundException
     */
    public function currentState(Model $model): string
    {
        return $this->stateResolver->make($model)->currentState();
    }

    /**
     * @throws StateNotFoundException
     * @throws InvalidConfigurationException
     */
    public function stateTag(Model $model): array|Exception
    {
        $state = $this->stateResolver->make($model);

        if (!$state instanceof StateTaggable) {
            throw InvalidConfigurationException::whenClassDoesNotImplementBaseInterface($state, StateTaggable::class);
        }

        return $state->stateTag();
    }

    /**
     * @throws StateNotFoundException
     */
    public function getStates(Model $model): Collection
    {
        return $this->stateResolver->make($model)->getStates();
    }

    /**
     * @throws StateNotFoundException
     */
    public function transitionableStates(Model $model): array
    {
        return $this->stateResolver->make($model)->transitionableStates();
    }
}
