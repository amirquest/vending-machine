<?php

namespace App\Events;

use App\Services\StateMachine\AbstractStateMachine as State;
use App\Services\StateMachine\Transition;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class StateChangedEvent
{
    use SerializesModels;

    public function __construct(
        public ?State                      $initialState,
        public ?State                      $finalState,
        public Transition                  $transition,
        public Model                       $model,
        public Authenticatable|string|null $causer = null
    ) {
    }
}
