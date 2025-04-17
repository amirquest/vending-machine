<?php

namespace Tests;

use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Database\Events\DatabaseRefreshed;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\ActingAsTrait;

abstract class FeatureTestCase extends TestCase
{
    use LazilyRefreshDatabase;
    use ActingAsTrait;


    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        Http::preventStrayRequests();

        Event::listen(DatabaseRefreshed::class, function () {
            $this->artisan('db:seed', ['--class' => RoleAndPermissionSeeder::class]);
            $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();
        });
    }
}
