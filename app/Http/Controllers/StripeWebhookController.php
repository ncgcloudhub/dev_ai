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
    $user = User::where('stripe_id', $invoice->customer)->first();

    if (!$user) return;

    $priceId = $invoice->lines->data[0]->price->id;
    $newPlan = PricingPlan::where('stripe_price_id', $priceId)->first();

    if (!$newPlan) return;

    // ✅ Cancel any other active subscriptions EXCEPT the one that triggered this invoice
    $subscriptions = \Stripe\Subscription::all([
        'customer' => $user->stripe_id,
        'status' => 'active',
    ]);

    foreach ($subscriptions as $subscription) {
        if ($subscription->id !== $invoice->subscription) {
            \Stripe\Subscription::update($subscription->id, ['cancel_at_period_end' => false]);
            \Stripe\Subscription::cancel($subscription->id);
        }
    }

    // Update user credits/tokens
    if ($settings->rollover_enabled) {
        $user->increment('credits_left', $newPlan->images);
        $user->increment('tokens_left', $newPlan->tokens);
    } else {
        $user->credits_left = $newPlan->images;
        $user->tokens_left = $newPlan->tokens;
        $user->save();
    }

    // Log to package history
    PackageHistory::create([
        'user_id' => $user->id,
        'package_id' => $newPlan->id,
        'invoice' => $invoice->id,
        'package_amount' => $newPlan->price,
    ]);

    $user->notify(new TokenRenewed($newPlan->tokens, $newPlan->images));
    Mail::to($user->email)->send(new TokensRenewedMail($user, $newPlan->tokens, $newPlan->images));
}


protected function handleSubscriptionDeleted($subscription)
{
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    $user = User::where('stripe_id', $subscription->customer)->first();

    if (!$user) return;

    $user->subscriptions()
        ->where('stripe_id', $subscription->id)
        ->update(['stripe_status' => 'canceled']);

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
                Log::info('Subscribed user to free plan after having no active subscription.', ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to resubscribe to free plan: ' . $e->getMessage());
        }
    }
}



}
