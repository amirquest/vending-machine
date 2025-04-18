<?php

namespace App\Listener;

use App\Events\StateChangedEvent;
use App\Services\Logger\StatusLogger\StatusLoggerInterface;

/** @note This listener should not be queued */
readonly class StateChangedListener
{
    public function __construct(private StatusLoggerInterface $statusLogger)
    {
    }

    public function handle(StateChangedEvent $event): void
    {
        $statusLog = $this->statusLogger
            ->performedOn($event->model)
            ->causedBy($event->causer)
            ->from($event->initialState->toString())
            ->to($event->finalState->toString())
            ->log();

        if (method_exists($event->transition, 'cancelReasons')) {
            $event->transition->setStatusLog($statusLog);
            app()->call([$event->transition, 'cancelReasons']);
        }

        if (method_exists($event->transition, 'notify')) {
            app()->call([$event->transition, 'notify']);
        }
    }
}
