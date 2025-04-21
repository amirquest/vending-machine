<?php

namespace App\Providers;

use App\Concerns\ResponseTrait;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class RouteServiceProvider extends ServiceProvider
{
    use ResponseTrait;

    public function boot(): void
    {
        if (!$this->app->isLocal()) {
            URL::forceScheme('https');
        }

        Route::pattern('identifier', '[0-9]+');

        $this->routes(function () {
            $this->mapCustomerRoutes();
            $this->mapCallBackRoutes();
        });

        $this->configureRateLimiting();
    }

    private function mapCustomerRoutes(): void
    {
        Route::prefix('customer')
            ->middleware('api')
            ->name('customer.')
            ->group(base_path('routes/customer/customerApi.php'));
    }

    private function mapCallBackRoutes(): void
    {
        Route::prefix('')
            ->middleware('api')
            ->group(base_path('routes/callback.php'));
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for(
            'api',
            fn(Request $request) => Limit::perMinute(60)
                ->by(optional($request->user())->id ?: $request->ip())
                ->response(fn(
                    Request $request,
                    array   $headers
                ) => $this->tooManyRequest(headers: $headers))
        );
    }
}
