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
            $this->mapUserRoutes();
            $this->mapAdminRoutes();
            $this->mapCallBackRoutes();
            $this->mapSurveyRoutes();
        });

        $this->configureRateLimiting();
    }

    private function mapUserRoutes(): void
    {
        Route::prefix('user')
            ->middleware('api')
            ->name('user.')
            ->group(base_path('routes/user/userApi.php'));
    }

    private function mapAdminRoutes(): void
    {
        Route::prefix('admin')
            ->middleware(['api', 'setLocale:en'])
            ->name('admin.')
            ->group(base_path('routes/admin/adminApi.php'));
    }

    private function mapCallBackRoutes(): void
    {
        Route::prefix('')
            ->middleware('api')
            ->group(base_path('routes/callback.php'));
    }

    private function mapSurveyRoutes(): void
    {
        Route::prefix('')
            ->name('survey.')
            ->middleware('auth.survey')
            ->group(base_path('routes/survey.php'));
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

        RateLimiter::for(
            'OTP-request',
            fn(Request $request) => Limit::perMinutes(10, 5)
                ->by(($request->get('mobile')) ?: $request->ip())
                ->response(fn(
                    Request $request,
                    array   $headers
                ) => $this->tooManyRequest(headers: $headers))
        );

        RateLimiter::for(
            'OTP-verify',
            fn(Request $request) => Limit::perMinutes(10, 5)
                ->by(($request->get('mobile')) ?: $request->ip())
                ->response(fn(
                    Request $request,
                    array   $headers
                ) => $this->tooManyRequest(headers: $headers))
        );

        RateLimiter::for(
            'payment-purchase-request',
            fn(Request $request) => Limit::perDay(50)
                ->by($request->user()->id)
                ->response(fn(
                    Request $request,
                    array   $headers
                ) => $this->tooManyRequest(headers: $headers))
        );
    }
}
