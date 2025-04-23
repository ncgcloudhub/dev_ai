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
    $user->increment('credits_left', $pricingPlan->images);
    $user->increment('tokens_left', $pricingPlan->tokens);

    // Log to package history
    PackageHistory::create([
        'user_id' => $user->id,
        'package_id' => $pricingPlan->id,
        'invoice' => $invoice->id,
        'package_amount' => $pricingPlan->price,
    ]);

    // Send the email
    Mail::to($user->email)->send(new TokensRenewedMail($user, $pricingPlan->tokens, $pricingPlan->images));

    Log::info('Credits/tokens updated for user.', ['user_id' => $user->id]);
}

    protected function handleSubscriptionDeleted($subscription)
    {
        $user = User::where('stripe_id', $subscription->customer)->first();

        if ($user) {
            // Optionally, mark the subscription as canceled in your database
            $user->subscriptions()->where('stripe_id', $subscription->id)->delete();
        }
    }
}
