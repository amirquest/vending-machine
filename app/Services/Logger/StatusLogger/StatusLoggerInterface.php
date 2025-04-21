<?php

namespace App\Services\Logger\StatusLogger;

interface StatusLoggerInterface
{
    public function from(string $from): static;

    public function to(string $to): static;
}
