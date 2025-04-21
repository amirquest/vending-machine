<?php

namespace App\Services\Logger\StatusLogger;

use App\Support\AbstractDTO;
use Illuminate\Database\Eloquent\Model;

class StatusLogPayload extends AbstractDTO
{
    public function __construct(
        protected readonly string $from,
        protected readonly string $to,
        protected readonly Model $subject,
        protected readonly ?Model $causer = null,
    ) {
    }

    public function getSubject(): Model
    {
        return $this->subject;
    }

    public function getCauser(): ?Model
    {
        return $this->causer;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getFrom(): string
    {
        return $this->from;
    }
}
