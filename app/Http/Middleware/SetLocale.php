<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next, string $lang): mixed
    {
        app()->setLocale($lang);

        return $next($request);
    }
}
