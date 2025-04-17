<?php

namespace App\Providers;

use App\Models\Admin;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }


    public function boot(): void
    {
        $this->setRelationMorphMap();
    }

    private function setRelationMorphMap(): void
    {
        Relation::morphMap([
            'admin' => Admin::class,
        ]);
    }
}
