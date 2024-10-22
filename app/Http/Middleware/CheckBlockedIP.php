<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

class CheckBlockedIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Get the IP address of the incoming request
    $ip = $request->ip();
    $location = Location::get($ip);

    if ($location) {
        $country = $location->regionName . ', ' . $location->countryName;
    } else {
        // Set to null or a default value if location data is not found
        $country = 'Unknown Location'; // Or you could set a default value like 'Unknown Location' # Saiful
    }
    
    // Check if there is any user with this IP address that is blocked
    $blockedUser = User::where('country', $country)
                        ->where('block', true)
                        ->first();

    // If the user's IP is blocked, log out the user and redirect
    if ($blockedUser) {
        Auth::logout(); // Log out the user if they are logged in
        return redirect()->route('blocked.page')->with('error', 'Your IP address is blocked.');
    }
    
        return $next($request);
    }
}
