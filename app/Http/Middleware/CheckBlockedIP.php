<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

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

    // Check if there is any user with this IP address that is blocked
    $blockedUser = User::where('ip_address', $ip)
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
