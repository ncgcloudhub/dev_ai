<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
            
            // Check if the user was referred
            $referral = Referral::where('referral_id', $request->user()->id)->first();
            if ($referral) {
                // Credit the referrer
                $referrer = User::find($referral->referrer_id);
                $referrer->increment('credits_left', 10);
                $referral->update(['status' => 'completed']);
            }
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
