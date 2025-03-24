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
        $currentUrl = $request->getRequestUri();
        $lowercaseUrl = strtolower($currentUrl);

        if ($currentUrl !== $lowercaseUrl) {
            return redirect($lowercaseUrl, 301);
        }

        return $next($request);
    }
}
