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
        $this->statusLogger
            ->performedOn($event->model)
            ->causedBy($event->causer)
            ->from($event->initialState->toString())
            ->to($event->finalState->toString())
            ->log();
    }
}
