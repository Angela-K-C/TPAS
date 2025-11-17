<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Check which guard is being used
            if ($request->routeIs('guest.*') || $request->is('guest/*')) {
                return route('guest.login'); // Redirect guests to guest login
            }

            return route('guest.login'); // Default for others (students/admins)
        }
    }
}
