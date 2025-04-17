<?php

use App\Http\Middleware\AlreadyAuthenticated;
use App\Http\Middleware\ConvertStrings;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SurveyAuthenticationMiddleware;
use Facades\App\Support\Responder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Exceptions\UrlGenerationException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Console\Scheduling\Schedule;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ConvertStrings::class);
        $middleware->alias([
            'guest' => AlreadyAuthenticated::class,
            'setLocale' => SetLocale::class,
            'auth.survey' => SurveyAuthenticationMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);

        $exceptions->render(function (ValidationException $e, Request $request) {
            return Responder::error(__('exception.validation'), $e->errors(), $e->status);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return Responder::unauthorized(__('exception.authentication'));
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            return Responder::notFound(__('exception.model_not_found'));
        });

        $exceptions->render(function (UrlGenerationException $e, Request $request) {
            return Responder::error(__('exception.url_generation'));
        });

        $exceptions->render(function (AuthorizationException|AccessDeniedHttpException $e, Request $request) {
            return Responder::forbidden(__('exception.authorization'));
        });

        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            return Responder::forbidden(__('exception.unauthorized'));
        });

        $exceptions->render(function (DecryptException $e, Request $request) {
            return Responder::notAcceptable(__('exception.survey_authentication_failed'));
        });
    })
    ->create();
