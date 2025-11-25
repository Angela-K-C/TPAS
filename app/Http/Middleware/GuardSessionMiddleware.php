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
        $guardContext = match (true) {
            $request->is('admin/*') || $request->routeIs('admin.*') => ['tpas-admin-session', '/admin'],
            $request->is('security/*') || $request->routeIs('security.*') => ['tpas-security-session', '/security'],
            $request->is('guest/*') || $request->routeIs('guest.*') => ['tpas-guest-session', '/guest'],
            default => ['tpas-university-session', '/'],
        };

        [$cookie, $path] = $guardContext;

        // Use guard-scoped cookies and paths so sessions don't overlap across roles.
        config([
            'session.cookie' => $cookie,
            'session.path' => $path,
        ]);

        return $next($request);
    }
}
