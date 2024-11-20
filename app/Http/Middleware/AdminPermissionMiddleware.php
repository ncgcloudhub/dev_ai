<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class AdminPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        // Check if the user is an admin
        if ($user->hasRole('admin')) {
            // Apply permission check for admins
            if (!$user->can($permission)) {
                throw UnauthorizedException::forPermissions([$permission]);
            }
        }

        return $next($request); // Allow all users (admins and regular users) to continue
       
    }
}
