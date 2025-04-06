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
        // Exclude these named routes from being lowercased
        $excludedRouteNames = [
            'checkout',
        ];
    
        // Exclude fixed paths
        $excludedPaths = [
            'google/callback',
            'google/login',
            'github/callback',
            'github/login',
        ];
    
        $route = $request->route();
        if ($route && in_array($route->getName(), $excludedRouteNames)) {
            return $next($request);
        }
    
        $currentPath = trim($request->path(), '/');
        if (in_array($currentPath, $excludedPaths)) {
            return $next($request);
        }
    
        // Only lowercase the path (not query string or dynamic segments)
        $currentUrl = $request->getRequestUri();
        $lowercaseUrl = preg_replace_callback(
            '/[^?]+/',
            fn($matches) => strtolower($matches[0]),
            $currentUrl
        );
    
        if ($currentUrl !== $lowercaseUrl) {
            return redirect($lowercaseUrl, 301);
        }
    
        return $next($request);
    }
    
}
