<?php

namespace App\Http\Middleware;

use App\Concerns\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlreadyAuthenticated
{
    use ResponseTrait;

    public function handle(Request $request, Closure $next, ...$guards): mixed
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $this->notAcceptable(__('auth.already_authenticated'));
            }
        }

        return $next($request);
    }

}
