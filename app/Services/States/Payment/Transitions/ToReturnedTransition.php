<?php

namespace App\Services\States\Payment\Transitions;

use App\Models\Order;
use App\Services\StateMachine\AbstractStateMachine as State;
use App\Services\StateMachine\Transition;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class ToReturnedTransition extends Transition
{
    public function __construct(protected Model|Order $model, protected State $newState, $args)
    {
    }

    /**
     * @throws Throwable
     */
    public function handle(): Model|Order {
        $this->model->status = $this->newState->toString();
        $this->model->save();

        return $this->model;
    }

    public function notify(): void
    {
        // notify customer for get back money
    }
}
