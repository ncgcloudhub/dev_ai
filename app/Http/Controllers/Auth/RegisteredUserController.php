<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Attempt to retrieve user's IP address from request headers
        $ipAddress = $request->ip();


        $user = User::create([
            // 'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'role' => 'user',
            'status' => 'active',
            'credits_left' => 100,
            'tokens_left' => 5000,
            'password' => Hash::make($request->password),
            'ipaddress' => $ipAddress, // Store IP address
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('warning', 'Please verify your email to login | Check email Inbox & Spam');
    }
}
