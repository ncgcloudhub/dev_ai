<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->status === 'inactive') {
            // You can redirect the user to a specific route or show an error message
            return redirect()->route('inactive');
            // Alternatively, you can abort the request with an error message
            // return abort(403, 'Your account is inactive.');
        }

        return $next($request);
    }
}
