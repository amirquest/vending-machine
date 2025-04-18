<?php

namespace App\Repositories\Contracts;

interface AssociateLog
{
    public function makeAssociateLog(mixed $payload): mixed;
}
