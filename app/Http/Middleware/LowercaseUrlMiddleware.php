<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;

class LowercaseUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // List of named routes or route patterns to exclude
        $excludedRouteNames = [
            'checkout',
        ];

        $excludedPaths = [
            'google/callback',
            'google/login',
            'github/callback',
            'github/login',
        ];

        // Get the current route name
        $route = $request->route();

        if ($route && in_array($route->getName(), $excludedRouteNames)) {
            return $next($request);
        }

        // Get the current route path
        $currentPath = trim($request->path(), '/');

        // If the current path is in the excluded paths list, bypass the middleware
        if (in_array($currentPath, $excludedPaths)) {
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
