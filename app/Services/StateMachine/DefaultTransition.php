<?php

namespace App\Services\StateMachine;

use Illuminate\Database\Eloquent\Model;
use App\Services\StateMachine\AbstractStateMachine as State;

class DefaultTransition extends Transition
{
    public function __construct(protected Model $model, protected State $newState)
    {
    }

    public function handle(): Model
    {
        $this->model->status = (string) $this->newState;

        $this->model->save();

        return $this->model;
    }
}
