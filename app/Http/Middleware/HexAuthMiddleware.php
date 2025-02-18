<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HexAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $providedHex = $request->header('X-Auth-Hex'); // Get the hex token from the request header
        $validHex = config('app.api_hex_key'); // Get stored hex key from .env

        // Check if the provided hex matches the stored key
        if (!$providedHex || $providedHex !== $validHex) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Invalid Hex Key'
            ], 401);
        }

        return $next($request);
    }
}
