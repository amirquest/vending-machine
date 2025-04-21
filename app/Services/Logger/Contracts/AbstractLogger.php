<?php

namespace App\Services\Logger\Contracts;

use App\Repositories\Contracts\AssociateLog;
use App\Services\CauserResolver\CauserResolverService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractLogger implements LoggerInterface
{
    protected ?Authenticatable $causer = null;

    protected Model $subject;

    protected bool $anonymous = false;

    public function __construct(
        protected CauserResolverService $causerResolver,
        protected AssociateLog $logRepository
    ) {
    }

    public function causedBy(Authenticatable|int|string|null $modelOrId): static
    {
        if (is_null($modelOrId)) {
            return $this->causedByAnonymous();
        }

        $this->causer = $this->causerResolver->resolve($modelOrId);

        $this->anonymous = false;

        return $this;
    }

    public function causedByAnonymous(): static
    {
        $this->anonymous = true;

        return $this;
    }

    public function performedOn(Model $subject): static
    {
        $this->subject = $subject;

        if (is_null($this->causer) && !$this->anonymous) {
            $this->causedBy($this->causerResolver->resolve());
        }

        return $this;
    }

    abstract public function log(): mixed;
}
