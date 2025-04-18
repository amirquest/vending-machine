<?php

namespace App\Services\Logger\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

interface LoggerInterface
{
    public function performedOn(Model $subject): static;

    public function causedByAnonymous(): static;

    public function causedBy(Authenticatable|int|string|null $modelOrId): static;

    public function log(): mixed;
}
