### Setup Model State Machine

Set up your allowed transitions like below:
```php
<?php

namespace App\Services\StateMachine\YourModel;

use App\Services\StateMachine\AbstractStateMachine;
use App\Services\StateMachine\StateTransition;

abstract class ModelStateMachine extends AbstractStateMachine
{
    public static function transitions(): StateTransition
    {
        return parent::transitions()
                     ->default(StateA::class)
                     ->allowTransition(StateA::class, StateB::class)
                     ->allowTransition([StateA::class, StateB::class], StateC::class)
                     ->allowTransition([
                         [StateW::class,StateP::class],
                         [StateR::class,StateQ::class,CustomeTransition::class],
                     ]);
    }
}
```

### Preparing Model

Make sure your model use `HasState` trait and set its `$stateMachine` attribute:
```php
<?php

namespace App\Models;

use App\Services\StateMachine\Concerns\HasState;
use Illuminate\Database\Eloquent\Model;
use App\Services\StateMachine\YourModel\ModelStateMachine;

class TestModel extends Model
{
    use HasState;

    public static $stateMachine = ModelStateMachine::class;
}

```

### Usage
 By using `StateMachineService` you can manage your model state machine. Also there is a validation rule to validate incoming request data. Only valid state values of `ModelStateMachine` implementations will be allowed.

```php
use App\Services\StateMachine\StateMachineService;
use App\Rules\ValidStateRule;
use App\Services\StateMachine\YourModel\ModelStateMachine;

Route::post('/', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'status' => ValidStateRule::make(ModelStateMachine::class)->nullable(),
        // OR
         'status' => new ValidStateRule(ModelStateMachine::class)
    ]);

    $service = StateMachineService::for(new TestModel(['status' => StateA::getMorphClass()]));

    $modelB = $service->transitionTo(StateB::getMorphClass()); // TestModel

    $service->canTransitionTo('StateB'); // true or false

    $service->currentState(); // StateB

    $service->getStates(); // it's an collection of states

    $service->transitionableStates(); // it's an array of states that current state is allowed.
});
```

### A sample of State

```php
<?php

namespace App\Services\StateMachine\YourModel;

use App\Services\StateMachine\YourModel\ModelStateMachine;

class StateA extends ModelStateMachine
{
    public static $name= 'STATE_A';
}
```
> The static `$name` attribute is optional. You are allowed to don't define it. It will be resolved based on the class name.

### A sample of Custom Transition

```php
<?php

namespace App\Services\StateMachine\YourModel;

class CustomTransition extends \App\Services\StateMachine\Transition
{
    private string $message;

    public function __construct(protected TestModel $model, ...$transitionArgs)
    {
        if (array_key_exists(0, $transitionArgs)) {
            $this->message = $transitionArgs[0];
        }
    }

    public function canTransition() : bool
    {
        return true; //you may check any condition here to transition states.
    }

    public function handle(\Any\DummyDependency $dummyDependency): TestModel
    {
        Assert::assertNotNull($dummyDependency);

        $this->model->fill([
            'status'  => StateY::getMorphClass(),
            'message' => $this->message,
        ])->save();

        return $this->model->refresh();
    }
}
```

### Transition Event
When a transition is successfully performed, an event will be dispatched called `App\Events\StateChanged`.




