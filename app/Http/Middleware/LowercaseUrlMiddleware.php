<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class LowercaseUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Exclude specific routes from being lowercased
        $excludedRoutes = [
            'google/callback',
            'google/login',
            'github/callback',
            'github/login',
        ];

        // Get the trimmed current path (no leading or trailing slashes)
        $currentPath = trim($request->path(), '/');

        // If exact match in excluded list, bypass middleware
        if (in_array($currentPath, $excludedRoutes)) {
            return $next($request);
        }

        // Exclude entire route groups (e.g., anything starting with "checkout/")
        if (Str::startsWith($currentPath, 'checkout')) {
            return $next($request);
        }

        // Get path only (without query string)
        $currentPathOnly = $request->getPathInfo(); // e.g., /SomePath/To/Lowercase
        $lowercasePath = strtolower($currentPathOnly);

        // Redirect if path has any uppercase characters
        if ($currentPathOnly !== $lowercasePath) {
            $queryString = $request->getQueryString(); // Leave query string unchanged
            $redirectUrl = $lowercasePath . ($queryString ? '?' . $queryString : '');
            return redirect($redirectUrl, 301);
        }

        return $next($request);
    }
}
