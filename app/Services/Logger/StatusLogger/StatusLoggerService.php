<?php

namespace App\Services\Logger\StatusLogger;

use App\Models\StatusLog;
use App\Repositories\StatusLogRepository;
use App\Services\CauserResolver\CauserResolverService;
use App\Services\Logger\Contracts\AbstractLogger;

class StatusLoggerService extends AbstractLogger implements StatusLoggerInterface
{
    protected string $from;

    protected string $to;

    public function __construct(CauserResolverService $causerResolver, StatusLogRepository $logRepository)
    {
        parent::__construct($causerResolver, $logRepository);
    }

    public function from(string $from): static
    {
        $this->from = $from;

        return $this;
    }

    public function to(string $to): static
    {
        $this->to = $to;

        return $this;
    }

    public function log(): StatusLog
    {
        $statusLogPayload = StatusLogPayload::fromArray([
            'from' => $this->from,
            'to' => $this->to,
            'subject' => $this->subject,
            'causer' => !$this->anonymous ? $this->causer : null,
        ]);

        return $this->logRepository->makeAssociateLog($statusLogPayload);
    }
}
