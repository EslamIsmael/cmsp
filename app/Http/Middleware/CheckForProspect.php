<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckForProspect
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
        abort_if(!(Auth::check() && Auth::user()->hasRole('Prospect')), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        return $next($request);
    }
}
