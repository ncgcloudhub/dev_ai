<?php

namespace App\Http\Controllers;

use App\Models\PackageHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\PricingPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Mail\TokensRenewedMail;
use App\Models\SiteSettings;
use App\Notifications\SubscriptionCancelled;
use App\Notifications\TokenRenewed;
use Illuminate\Support\Facades\Mail;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
{
    Log::info('Webhook received. Starting processing...');

    $payload = $request->getContent();
    $sigHeader = $request->header('Stripe-Signature');
    $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

    Log::info('Webhook payload:', ['payload' => $payload]);
    Log::info('Webhook signature header:', ['sigHeader' => $sigHeader]);

    try {
        Log::info('Attempting to construct Stripe event...');
        $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        Log::info('Stripe event constructed successfully:', ['event_id' => $event->id, 'event_type' => $event->type]);
    } catch (\Exception $e) {
        Log::error('Webhook error: ' . $e->getMessage());
        return response('Webhook error', 400);
    }

    Log::info('Handling event type:', ['event_type' => $event->type]);

    switch ($event->type) {
        case 'invoice.payment_succeeded':
            Log::info('Handling invoice.payment_succeeded event...');
            $this->handleInvoicePaymentSucceeded($event->data->object);
            break;

        case 'customer.subscription.deleted':
            Log::info('Handling customer.subscription.deleted event...');
            $this->handleSubscriptionDeleted($event->data->object);
            break;
            
        default:
            Log::info('Unhandled event type:', ['event_type' => $event->type]);
            break;
    }

    Log::info('Webhook processing completed.');
    return response('Webhook handled', 200);
}

protected function handleInvoicePaymentSucceeded($invoice)
{   
    $settings = SiteSettings::first();
    Log::info('Handling invoice payment', ['invoice_id' => $invoice->id]);

    // Get user
    $user = User::where('stripe_id', $invoice->customer)->first();
    if (!$user) {
        Log::error('User not found for Stripe customer:', ['customer' => $invoice->customer]);
        return;
    }

    // Get price ID directly from invoice (not subscription)
    $priceId = $invoice->lines->data[0]->price->id;

    // Find pricing plan
    $pricingPlan = PricingPlan::where('stripe_price_id', $priceId)->first();
    if (!$pricingPlan) {
        Log::error('Pricing plan not found for price ID:', ['price_id' => $priceId]);
        return;
    }

    // Update user credits/tokens
    if ($settings->rollover_enabled) {
        $user->increment('credits_left', $pricingPlan->images);
        $user->increment('tokens_left', $pricingPlan->tokens);
    } else {
        $user->credits_left = $pricingPlan->images;
        $user->tokens_left = $pricingPlan->tokens;
        $user->save();
    }

    // Log to package history
    PackageHistory::create([
        'user_id' => $user->id,
        'package_id' => $pricingPlan->id,
        'invoice' => $invoice->id,
        'package_amount' => $pricingPlan->price,
    ]);

    $user->notify(new TokenRenewed($pricingPlan->tokens, $pricingPlan->images));

    // Send the email
    Mail::to($user->email)->send(new TokensRenewedMail($user, $pricingPlan->tokens, $pricingPlan->images));

    Log::info('Credits/tokens updated for user.', ['user_id' => $user->id]);
}

protected function handleSubscriptionDeleted($subscription)
{
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    
    $user = User::where('stripe_id', $subscription->customer)->first();

    if (!$user) {
        Log::warning('User not found for subscription deletion.');
        return;
    }

    // Mark the subscription as canceled in your DB
    $user->subscriptions()
        ->where('stripe_id', $subscription->id)
        ->update(['stripe_status' => 'canceled']);

    $priceId = $subscription->items->data[0]->price->id ?? null;

    Log::info('Stripe subscription cancellation - Price ID extracted:', ['price_id' => $priceId]);

    $packageName = "Unknown Package";

    if ($priceId) {
        $pricingPlan = PricingPlan::where('stripe_price_id', $priceId)->first();

        if ($pricingPlan) {
            $packageName = $pricingPlan->title;
            Log::info('Pricing plan found for cancellation:', ['package_name' => $packageName]);
        } else {
            Log::warning('No matching pricing plan found for price ID during cancellation.', ['price_id' => $priceId]);
        }
    }

    // Notify user
    $user->notify(new SubscriptionCancelled($packageName));

    // ✅ Only subscribe to free plan if no other active subscriptions exist
    $activeSubscriptions = \Stripe\Subscription::all([
        'customer' => $user->stripe_id,
        'status' => 'active',
    ]);

    if (empty($activeSubscriptions->data)) {
        try {
            $freePlan = PricingPlan::where('slug', 'free_monthly')->first();

            if ($freePlan && $freePlan->stripe_price_id) {
                \Stripe\Subscription::create([
                    'customer' => $user->stripe_id,
                    'items' => [
                        ['price' => $freePlan->stripe_price_id],
                    ],
                    'payment_behavior' => 'default_incomplete',
                    'expand' => ['latest_invoice.payment_intent'],
                ]);

                Log::info('User subscribed to free plan after having no active subscriptions.', ['user_id' => $user->id]);
            } else {
                Log::error('Free monthly plan not found or missing Stripe price ID during fallback.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to resubscribe user to free plan: ' . $e->getMessage());
        }
    } else {
        Log::info('User has other active subscriptions. Skipping free plan subscription.', ['user_id' => $user->id]);
    }
}


}
