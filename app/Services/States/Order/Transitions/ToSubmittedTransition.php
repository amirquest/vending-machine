<?php

namespace App\Services\States\Order\Transitions;

use App\Models\Order;
use App\Services\StateMachine\Transition;
use App\Services\States\Order\OrderStateMachine;
use App\Services\StateMachine\AbstractStateMachine as State;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class ToSubmittedTransition extends Transition
{
    protected int $vendingMachineId;


    /** @throws Throwable */
    public function __construct(protected Model|Order $model, protected State $newState, $args)
    {
        $args = collect($args);
        if (! empty($args[0])) {
            $this->vendingMachineId = $args[0]['vending_machine_id'];
        }
    }

    public function handle(): Model|Order
    {
        $this->model->status = $this->newState->toString();
        $this->model->vending_machine_id = $this->vendingMachineId;
        $this->model->save();


        return $this->model;
    }
}
