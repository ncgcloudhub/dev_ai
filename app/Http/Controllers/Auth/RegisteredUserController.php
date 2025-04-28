<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NewsLetter;
use App\Models\PackageHistory;
use App\Models\PricingPlan;
use App\Models\Referral;
use App\Models\User;
use App\Notifications\TokenRenewed;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Stevebauman\Location\Facades\Location;

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
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        'name' => ['required', 'string', 'max:255', 'unique:' . User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $ipAddress = $request->ip();
    $location = Location::get($ipAddress);
    $regionAndCountry = $location ? $location->regionName . ', ' . $location->countryName : null;

    // Create user WITHOUT credits and tokens initially
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'username' => $request->username,
        'role' => 'user',
        'status' => 'active',
        'password' => Hash::make($request->password),
        'ipaddress' => $ipAddress,
        'country' => $regionAndCountry,
    ]);

    $user->referral_link = route('register', ['ref' => $user->id]);
    $user->save();

    // Handle referrals
    if ($request->ref) {
        Referral::create([
            'referrer_id' => $request->ref,
            'referral_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    // Subscribe to newsletter
    NewsLetter::create([
        'email' => $user->email,
        'ipaddress' => $ipAddress,
    ]);

    // -----------------------------------
    //  STEP 1: Get the Free Monthly Package
    // -----------------------------------
    // $freePlan = PricingPlan::where('slug', 'free_monthly')->first();

    // if ($freePlan) {
    //     // Add credits and tokens based on free plan
    //     $user->increment('credits_left', $freePlan->images ?? 0);
    //     $user->increment('tokens_left', $freePlan->tokens ?? 0);
    //     Log::info('User credits/tokens updated from register controller:', [
    //         'user_id' => $user->id,
    //         'credits_left' => $user->credits_left,
    //         'tokens_left' => $user->tokens_left,
    //     ]);

    //     // Save package history
    //     PackageHistory::create([
    //         'user_id' => $user->id,
    //         'package_id' => $freePlan->id,
    //         'invoice' => null, // Free users won't have Stripe invoice
    //         'package_amount' => 0, // Free, so 0
    //     ]);

    //     // (Optional) Notify user about free subscription
    //     $user->notify(new TokenRenewed($freePlan->tokens ?? 0, $freePlan->images ?? 0));
    // } else {
    //     Log::error('Free monthly plan not found during registration!');
    // }

    // -----------------------------------
    //  STEP 2: Create Stripe Customer & Free Subscription
    // -----------------------------------
    try {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    
        if (!$user->stripe_id) {
            $customer = \Stripe\Customer::create([
                'email' => $user->email,
                'name' => $user->name,
            ]);
    
            $user->stripe_id = $customer->id;
            $user->save();
        }
    
        // Fetch the free plan from the database
        $freePlan = PricingPlan::where('slug', 'free_monthly')->first();
    
        if ($freePlan && $freePlan->stripe_price_id) {
            // Subscribe user to free plan
            \Stripe\Subscription::create([
                'customer' => $user->stripe_id,
                'items' => [
                    ['price' => $freePlan->stripe_price_id],
                ],
                'payment_behavior' => 'default_incomplete',
                'expand' => ['latest_invoice.payment_intent'],
            ]);
        } else {
            Log::error('Free monthly plan not found or missing Stripe price ID during registration.');
        }
    
    } catch (\Exception $e) {
        Log::error('Stripe free subscription creation failed: ' . $e->getMessage());
    }
    

    event(new Registered($user));

    return redirect()->route('login')->with('warning', 'Please verify your email to login | Check email Inbox & Spam');
}

}
