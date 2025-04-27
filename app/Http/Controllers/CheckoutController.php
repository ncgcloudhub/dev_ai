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
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Invoice;


class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */

     public function __invoke(Request $request)
     {
         $id = $request->input('id');
         $prod_id = $request->input('prod_id');
         $price_id = $request->input('price_id');
         $user = $request->user();

         Stripe::setApiKey(config('services.stripe.secret'));

         // Check if user already has an active subscription
         $currentSubscription = $user->subscriptions()->where('stripe_status', 'active')->first();
     
         if ($currentSubscription) {
             try {
                 // Retrieve and cancel the subscription on Stripe
                 $stripeSubscription = Subscription::retrieve($currentSubscription->stripe_id);
                 $stripeSubscription->cancel();

             } catch (\Exception $e) {
                 return redirect()->back()->with('error', 'Failed to cancel previous subscription: ' . $e->getMessage());
             }
         }
         
        return $user->newSubscription($prod_id, $price_id)
        ->checkout([
            'success_url' => route('subscription.success', ['pricingPlanId' => $id]),
            'cancel_url' => route('user.dashboard'),
        ]);
    }

    // Handle the successful subscription and store the package
    public function handleSuccess(Request $request, $pricingPlanId)
    {
        $user = Auth::user();
        $pricingPlan = PricingPlan::findOrFail($pricingPlanId);

        // // Store subscription details
        // PackageHistory::insert([
        //     'user_id' => $user->id,
        //     'package_id' => $pricingPlan->id,
        //     'invoice' => 'CC' . mt_rand(10000000, 99999999),
        //     'package_amount' => $pricingPlan->price,
        //     'created_at' => Carbon::now(),
        // ]);

        // // Update user's credits and tokens
        // User::where('id', $user->id)->update([
        //     'credits_left' => DB::raw('credits_left + ' . $pricingPlan->images),
        //     'tokens_left' => DB::raw('tokens_left + ' . $pricingPlan->tokens)
        // ]);

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

public function subscriptionSummary()
{
    Stripe::setApiKey(config('services.stripe.secret'));
    
    $users = User::whereNotNull('stripe_id')->get();
    $summary = [];
    
    foreach ($users as $user) {
        $subscriptions = Subscription::all(['customer' => $user->stripe_id])->data;
        $invoices = Invoice::all(['customer' => $user->stripe_id])->data;
    
        $subscriptionDetails = [];
    
        foreach ($subscriptions as $subscription) {
            $item = $subscription->items->data[0] ?? null;
    
            if (!$item) continue;
    
            $price = Price::retrieve($item->price->id);
            $product = Product::retrieve($price->product);
    
            // Match invoice for this subscription
            $invoice = collect($invoices)->firstWhere('subscription', $subscription->id);
    
            $subscriptionDetails[] = [
                'package_name' => $product->name ?? 'N/A',
                'package_price' => number_format($price->unit_amount / 100, 2) . ' ' . strtoupper($price->currency),
                'purchased_on' => date('Y-m-d', $subscription->start_date),
                'renewal_date' => date('Y-m-d', $subscription->current_period_end),
                'invoice_url' => $invoice->hosted_invoice_url ?? null,
                'invoice_number' => $invoice->number ?? null,
            ];
        }
    
        $summary[] = [
            'user' => $user,
            'total_subscriptions' => count($subscriptions),
            'subscriptions' => $subscriptionDetails,
        ];
    }
    

    return view('backend.subscription.manage_stripe_subscription_admin', compact('summary'));
}

}
