<?php

namespace App\Http\Controllers;


use App\Models\PackageHistory;
use App\Models\PricingPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\BalanceTransaction;
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

    public function getBalanceReport(Request $request)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    // Get the selected date range from request
    $startDate = strtotime($request->start_date . ' 00:00:00'); // Convert to timestamp
    $endDate = strtotime($request->end_date . ' 23:59:59');

    try {
        // Fetch transactions within the selected date range
        $transactions = BalanceTransaction::all([
            'created' => [
                'gte' => $startDate, // greater than or equal to start date
                'lte' => $endDate,   // less than or equal to end date
            ],
            'limit' => 100, // Adjust the limit as needed
        ]);

        return view('backend.subscription.report', compact('transactions', 'startDate', 'endDate'));
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error fetching balance report: ' . $e->getMessage());
    }
}

}
