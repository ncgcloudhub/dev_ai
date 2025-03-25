<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LowercaseUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // List of routes to exclude from lowercasing
        $excludedRoutes = [
            'google/callback', // Exclude Google OAuth callback
            'google/login',    // Exclude Google OAuth login
        ];

        // Get the current route path
        $currentPath = trim($request->path(), '/');

        // If the current path is in the excluded list, bypass the middleware
        if (in_array($currentPath, $excludedRoutes)) {
            return $next($request);
        }

        $currentUrl = $request->getRequestUri();
        $lowercaseUrl = strtolower($currentUrl);

        if ($currentUrl !== $lowercaseUrl) {
            return redirect($lowercaseUrl, 301);
        }

        return $next($request);
    }
}
