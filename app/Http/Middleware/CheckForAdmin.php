<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckForAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        abort_if(!(Auth::check() && Auth::user()->hasAnyRole(['Admin', 'User'])), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $next($request);
    }
}
