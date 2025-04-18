<?php

namespace App\Services\StateMachine;

use App\Events\StateChangedEvent;
use App\Services\StateMachine\Contracts\ShouldUpdateStatusChangedAtInterface;
use App\Services\StateMachine\Contracts\StateMachineInterface;
use App\Services\StateMachine\Exceptions\CouldNotTransitionException;
use App\Services\StateMachine\Exceptions\NotAllowedTransitionException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionClass;
use Throwable;

/**
 * @property ?string $name
 */
abstract class AbstractStateMachine implements StateMachineInterface
{
    public static string $flow = 'default';

    private Authenticatable|string|null $causer = null;

    private StateTransition $stateTransition;

    public function __construct(protected Model $model)
    {
        $this->stateTransition = static::transitions();
    }

    public static function getMorphClass(): string
    {
        return static::$name ?? Str::upper(Str::snake(class_basename(static::class)));
    }

    public static function transitions(): StateTransition
    {
        $reflection = new ReflectionClass(static::class);
        $baseClass = $reflection->name;

        while ($reflection && !$reflection->isAbstract()) {
            $reflection = $reflection->getParentClass();

            $baseClass = $reflection->name;
        }

        return new StateTransition($baseClass);
    }

    public static function resolveStateClass($stateClass): ?string
    {
        if ($stateClass === null) {
            return null;
        }
        $mapping = static::transitions()->getStateMapping();

        if ($mapping->has($stateClass)) {
            $stateClass = $mapping->get($stateClass);
        }

        if (!$mapping->contains($stateClass)) {
            return null;
        }

        if (!class_exists($stateClass)) {
            return null;
        }

        return $stateClass;
    }

    public static function getStates(): Collection
    {
        return static::transitions()->getStateMapping()->keys();
    }

    /**
     * @throws Throwable
     * @throws CouldNotTransitionException
     */
    public function transitionTo($newState, ...$transitionArgs): Model
    {
        $newState = $this->resolveStateObject($newState);
        $from = static::getMorphClass();
        $to = $newState::getMorphClass();

        if (!$this->stateTransition->isTransitionAllowed($from, $to)) {
            throw CouldNotTransitionException::make($from, $to, get_class($this->model));
        }

        $transition = $this->resolveTransitionClass(
            $from,
            $to,
            $newState,
            ...$transitionArgs
        );

        // todo: refactor causer determiner
        if (!empty($transitionArgs[0])) {
            $this->causer = $transitionArgs[0]['causer'] ?? null;
        }

        if ($this->causer === null) {
            $this->causer = auth()->user();
        }

        if ($this->causer === 'sys') {
            $this->causer = null;
        }

        return $this->transition($transition);
    }

    public function canTransitionTo($newState, ...$transitionArgs): bool
    {
        $newState = $this->resolveStateObject($newState);

        $from = static::getMorphClass();

        $to = $newState::getMorphClass();

        if (!$this->stateTransition->isTransitionAllowed($from, $to)) {
            return false;
        }

        $transition = $this->resolveTransitionClass(
            $from,
            $to,
            $newState,
            ...$transitionArgs
        );

        if (method_exists($transition, 'canTransition')) {
            return app()->call([$transition, 'canTransition']);
        }

        return true;
    }

    public function currentState(): string
    {
        return $this->model->status;
    }

    public function __toString(): string
    {
        return self::getMorphClass();
    }

    public function toString(): string
    {
        return $this->__toString();
    }

    public function transitionableStates(): array
    {
        return $this->stateTransition->transitionableStates(static::getMorphClass());
    }

    private function resolveStateObject($state): self
    {
        $stateClassName = static::resolveStateClass($state);

        return new $stateClassName($this->model);
    }

    private function resolveTransitionClass(
        string $from,
        string $to,
        AbstractStateMachine $newState,
        ...$transitionArgs
    ) {
        $transitionClass = $this->stateTransition->resolveTransitionClass($from, $to);

        return $transitionClass === null ?
            new DefaultTransition($this->model, $newState) :
            new $transitionClass($this->model, ...[$newState, $transitionArgs]);
    }

    private function resolveModelStatusUpdatedAt(): void
    {
        if (!$this instanceof ShouldUpdateStatusChangedAtInterface) {
            return;
        }

        $this->model->status_changed_at = now()->toDateTimeString();
    }

    /**
     * @throws Throwable
     */
    private function transition(Transition $transition): Model
    {
        if (method_exists($transition, 'canTransition')) {
            if (!app()->call([$transition, 'canTransition'])) {
                throw NotAllowedTransitionException::make(get_class($transition), get_class($this->model));
            }
        }

        $this->resolveModelStatusUpdatedAt();

        try {
            DB::beginTransaction();
            $model = app()->call([$transition, 'handle']);

            event(new StateChangedEvent(
                $this,
                $transition->newState(),
                $transition,
                $this->model,
                $this->causer
            ));

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();

            throw $throwable;
        }

        return $model;
    }

    public static function setFlow(string $flow): void
    {
        self::$flow = $flow;
    }
}
