<?php

namespace App\Services\Identifier;

use Jenssegers\Optimus\Optimus;

class IdentifierService
{
    public const int PRIME = 1871293499;
    public const int INVERSE = 546327795;
    public const int RANDOM = 1233972926;

    public function __construct(private readonly Optimus $optimus)
    {
    }

    public function generateIdentifier(int $id): int
    {
        return $this->optimus->encode($id);
    }

    public function getId(int $identifier): int
    {
        return $this->optimus->decode($identifier);
    }
}
