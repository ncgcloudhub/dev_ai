<?php

namespace App\Http\Controllers;


use App\Models\PackageHistory;
use App\Models\PricingPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Subscription;


class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */

    public function __invoke(Request $request, $id, $prod_id, $price_id)
    {
        return $request->user()
            ->newSubscription($prod_id, $price_id)
            ->checkout([
                'success_url' => route('subscription.success', ['pricingPlanId' => $id]), // Redirect here after success
                'cancel_url' => route('user.dashboard'),
            ]);
    }

    // Handle the successful subscription and store the package
    public function handleSuccess(Request $request, $pricingPlanId)
    {
        $user = Auth::user();
        $pricingPlan = PricingPlan::findOrFail($pricingPlanId);

        // Store subscription details
        PackageHistory::insert([
            'user_id' => $user->id,
            'package_id' => $pricingPlan->id,
            'invoice' => 'CC' . mt_rand(10000000, 99999999),
            'package_amount' => $pricingPlan->price,
            'created_at' => Carbon::now(),
        ]);

        // Update user's credits and tokens
        User::where('id', $user->id)->update([
            'credits_left' => DB::raw('credits_left + ' . $pricingPlan->images),
            'tokens_left' => DB::raw('tokens_left + ' . $pricingPlan->tokens)
        ]);

        return redirect()->route('user.dashboard')->with([
            'message' => 'Your package has been successfully purchased',
            'alert-type' => 'success'
        ]);
    }

    public function cancelSubscription(Request $request, $subscriptionId)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Retrieve and cancel the subscription
            $subscription = Subscription::retrieve($subscriptionId);
            $subscription->cancel();

            return redirect()->back()->with('success', 'Subscription canceled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel subscription: ' . $e->getMessage());
        }

    }
}
