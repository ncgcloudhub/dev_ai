<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User; // Ensure the User model is imported

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) { // Check if a user is authenticated
            $user_id = Auth::user()->id;
            $user = User::findOrFail($user_id);
            $user->last_seen = Carbon::now();
            $user->save();
        }

        return $next($request);

    }
}
