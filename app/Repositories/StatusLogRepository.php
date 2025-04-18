<?php

namespace App\Repositories;

use App\Models\StatusLog;
use App\Repositories\Contracts\AbstractRepository;
use App\Repositories\Contracts\AssociateLog;

class StatusLogRepository extends AbstractRepository implements AssociateLog
{
    public function makeAssociateLog(mixed $payload): StatusLog
    {
        $statusLog = $this->instance([
            'from' => $payload->getFrom(),
            'to' => $payload->getTo(),
        ]);

        return tap($statusLog, function (StatusLog $statusLog) use ($payload) {
            $statusLog->subject()->associate($payload->getSubject());
            $statusLog->causer()->associate($payload->getCauser());

            $statusLog->save();
        });
    }

    protected function instance(array $attributes = []): StatusLog
    {
        return new StatusLog($attributes);
    }
}
