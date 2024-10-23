<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stevebauman\Location\Facades\Location;

class BlockCountries
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $location = Location::get($request->ip());
        
        if ($location && in_array($location->countryCode, ['NL','NL-NH','BD'])) {
            // Replace with the country codes you want to block
            abort(403, 'Access from your country is blocked.');
        }

        return $next($request);
    }
}
