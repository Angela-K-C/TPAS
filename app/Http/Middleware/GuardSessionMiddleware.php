<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuardSessionMiddleware
{
    /**
     * Use a distinct session cookie per guard so different roles can log in concurrently.
     */
    public function handle(Request $request, Closure $next)
    {
        $cookie = match (true) {
            $request->is('admin/*') || $request->routeIs('admin.*') => 'tpas-admin-session',
            $request->is('security/*') || $request->routeIs('security.*') => 'tpas-security-session',
            $request->is('guest/*') || $request->routeIs('guest.*') => 'tpas-guest-session',
            default => 'tpas-university-session',
        };

        config(['session.cookie' => $cookie]);

        return $next($request);
    }
}
