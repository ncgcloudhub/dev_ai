<?php

namespace App\Http\Controllers;

use App\Models\PackageHistory;
use App\Models\PricingPlan;
use App\Models\SubscriptionPlan;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    public function AllPackage()
    {

        $pricingPlans = PricingPlan::orderBy('id', 'asc')->get();
        $highestDiscount = PricingPlan::where('package_type', 'yearly')->where('discount_type', 'percentage')->max('discount');

        $monthlyPlans = $pricingPlans->filter(function ($plan) {
            return $plan->package_type === 'monthly';
        });

        $yearlyPlans = $pricingPlans->filter(function ($plan) {
            return $plan->package_type === 'yearly';
        });

        $totalTemplates = Template::count();

    // Get the authenticated user
    $user = Auth::user();

    Stripe::setApiKey(config('services.stripe.secret'));

    $lastPackageId = null; // default no plan selected

    try {
        if ($user->stripe_id) {
            Log::info('Fetching subscriptions for user', ['stripe_id' => $user->stripe_id]);
    
            $stripeSubscriptions = \Stripe\Subscription::all([
                'customer' => $user->stripe_id,
                'status' => 'active',
                'limit' => 1,
            ]);
    
            Log::info('Stripe subscriptions fetched', ['subscriptions' => $stripeSubscriptions->data]);
    
            $currentSubscription = collect($stripeSubscriptions->data)->first();
    
            if ($currentSubscription) {
                Log::info('Current active subscription found', ['currentSubscription' => $currentSubscription]);
    
                $stripePriceId = $currentSubscription->items->data[0]->price->id ?? null;
    
                if ($stripePriceId) {
                    Log::info('Stripe price ID from active subscription', ['stripePriceId' => $stripePriceId]);
    
                    $pricingPlan = PricingPlan::where('stripe_price_id', $stripePriceId)->first();
    
                    if ($pricingPlan) {
                        Log::info('Matched PricingPlan found', ['pricingPlanId' => $pricingPlan->id]);
                        $lastPackageId = $pricingPlan->id;
                    } else {
                        Log::warning('No PricingPlan matched for Stripe price ID', ['stripePriceId' => $stripePriceId]);
                    }
                } else {
                    Log::warning('No price ID found in subscription items', ['subscription' => $currentSubscription]);
                }
            } else {
                Log::info('No active subscriptions found for user', ['stripe_id' => $user->stripe_id]);
            }
        }
    } catch (\Exception $e) {
        Log::error('Error fetching Stripe subscription: ' . $e->getMessage());
        // fallback silently
    }

    return view('backend.subscription.all_package', compact(
        'monthlyPlans', 'yearlyPlans', 'lastPackageId', 'totalTemplates', 'highestDiscount'
    ));
    } // End Method  

    public function purchase($pricingPlanId)
    {
        $pricingPlan = PricingPlan::findOrFail($pricingPlanId);
        $id = Auth::user()->id;
        $user = User::find($id);

        return view('backend.subscription.package_invoice', compact('pricingPlan', 'user'));
    }

    public function StoreSubscriptionPlan(Request $request)
    {

        $user_id = Auth::user()->id;
        $pricingPlan = PricingPlan::findOrFail($request->pricing_plan_id);

        PackageHistory::insert([
            'user_id' => $user_id,
            'package_id' => $pricingPlan->id,
            'invoice' => 'CC' . mt_rand(10000000, 99999999),
            'package_amount' => $pricingPlan->price,
            'created_at' => Carbon::now(),
        ]);

        // Image Increment
        User::where('id', $user_id)->update([
            'credits_left' => DB::raw('credits_left + ' . $pricingPlan->images),
            'tokens_left' => DB::raw('tokens_left + ' . $pricingPlan->tokens)
        ]);

        $notification = array(
            'message' => 'Your package has been successfully purchased',
            'alert-type' => 'success'
        );

        return redirect()->route('all.package')->with($notification);
    } // End Method 

    
}
