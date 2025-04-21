<?php

namespace App\Services\StateMachine;

use App\Services\StateMachine\Exceptions\InvalidConfigurationException;
use Illuminate\Support\Collection;

class StateTransition
{
    public array $allowedTransitions = [];

    public ?string $defaultStateClass = null;

    private array $statesMapping = [];

    public function __construct(public string $baseStateClass)
    {
    }

    public function default(string $defaultStateClass): StateTransition
    {
        $this->defaultStateClass = $defaultStateClass;

        return $this;
    }

    public function getStateMapping(): Collection
    {
        return collect($this->statesMapping);
    }

    /**
     * @throws InvalidConfigurationException
     */
    public function allowTransition(string|array $from, string|array $to, ?string $transition = null): StateTransition
    {
        if (is_array($from)) {
            foreach ($from as $fromState) {
                $this->allowTransition($fromState, $to, $transition);
            }

            return $this;
        }

        if (is_array($to)) {
            foreach ($to as $toState) {
                $this->allowTransition($from, $toState, $transition);
            }

            return $this;
        }

        if (!is_subclass_of($from, $this->baseStateClass)) {
            throw InvalidConfigurationException::whenClassDoesNotExtendBaseClass($from, $this->baseStateClass);
        }

        if (!is_subclass_of($to, $this->baseStateClass)) {
            throw InvalidConfigurationException::whenClassDoesNotExtendBaseClass($to, $this->baseStateClass);
        }

        if (is_not_null($transition) && !is_subclass_of($transition, Transition::class)) {
            throw InvalidConfigurationException::whenTransitionDoesNotExtendBaseClass($transition);
        }

        $this->allowedTransitions[$this->createTransitionKey($from, $to)] = $transition;

        $this->statesMapping[$from::getMorphClass()] = $from;
        $this->statesMapping[$to::getMorphClass()] = $to;

        return $this;
    }

    public function allowTransitions(array $transitions): StateTransition
    {
        foreach ($transitions as $transition) {
            $this->allowTransition($transition[0], $transition[1], $transition[2] ?? null);
        }

        return $this;
    }

    public function isTransitionAllowed(string $fromMorphClass, string $toMorphClass): bool
    {
        $transitionKey = $this->createTransitionKey($fromMorphClass, $toMorphClass);

        return array_key_exists($transitionKey, $this->allowedTransitions);
    }

    public function resolveTransitionClass(string $fromMorphClass, string $toMorphClass): ?string
    {
        $transitionKey = $this->createTransitionKey($fromMorphClass, $toMorphClass);

        return $this->allowedTransitions[$transitionKey];
    }

    public function transitionableStates(string $fromMorphClass): array
    {
        $transitionableStates = [];

        foreach ($this->allowedTransitions as $allowedTransition => $value) {
            [$transitionFromMorphClass, $transitionToMorphClass] = explode('-', $allowedTransition);

            if ($transitionFromMorphClass !== $fromMorphClass) {
                continue;
            }

            $transitionableStates[] = $transitionToMorphClass;
        }

        return $transitionableStates;
    }

    private function createTransitionKey(string $from, string $to): string
    {
        if (is_subclass_of($from, $this->baseStateClass)) {
            $from = $from::getMorphClass();
        }

        if (is_subclass_of($to, $this->baseStateClass)) {
            $to = $to::getMorphClass();
        }

        return "{$from}-{$to}";
    }
}
