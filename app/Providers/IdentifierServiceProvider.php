<?php

namespace App\Providers;

use App\Services\Identifier\IdentifierService as Identifier;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Optimus\Optimus;

class IdentifierServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app
            ->when(Identifier::class)
            ->needs(Optimus::class)
            ->give(fn() => new  Optimus(Identifier::PRIME, Identifier::INVERSE, Identifier::RANDOM));
    }
}
