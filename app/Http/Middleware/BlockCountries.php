<?php

namespace App\Http\Middleware;

use App\Models\blockCountry;
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
        

        if ($location) {
            // Fetch the blocked country codes dynamically from the blockCountry table
            $blockedCountryCodes = blockCountry::pluck('country_code')->toArray();
            
            // Check if the user's country code is in the blocked country list
            if (in_array($location->countryCode, $blockedCountryCodes)) {
                abort(403, 'Access from your country is blocked.');
            }

        }
        return $next($request);
    }
}
