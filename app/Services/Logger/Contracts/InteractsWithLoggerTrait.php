<?php

namespace App\Services\Logger\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphTo;

trait InteractsWithLoggerTrait
{
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }
}
