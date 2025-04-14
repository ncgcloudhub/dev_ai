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
        $excludedRoutes = [
            'google/callback',
            'google/login',
            'github/callback',
            'github/login',
            'subscription/cancel', // <-- Add this line
        ];

        $currentPath = trim($request->path(), '/');

        // Exclude if matches explicitly
        if (in_array($currentPath, $excludedRoutes)) {
            return $next($request);
        }

        // Exclude patterns (e.g., route groups)
        if (
            Str::startsWith($currentPath, 'checkout') ||
            Str::startsWith($currentPath, 'subscription/cancel') // <-- Add this line
        ) {
            return $next($request);
        }

        $currentPathOnly = $request->getPathInfo();
        $lowercasePath = strtolower($currentPathOnly);

        if ($currentPathOnly !== $lowercasePath) {
            $queryString = $request->getQueryString();
            $redirectUrl = $lowercasePath . ($queryString ? '?' . $queryString : '');
            return redirect($redirectUrl, 301);
        }

        return $next($request);
    }

}
