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
    Log::info('Starting handleInvoicePaymentSucceeded...');
    Log::info('Invoice details:', [
        'invoice_id' => $invoice->id,
        'customer_id' => $invoice->customer,
        'subscription_id' => $invoice->subscription,
        'amount_paid' => $invoice->amount_paid,
    ]);

    // Find the user associated with the Stripe customer ID
    Log::info('Attempting to find user with Stripe ID:', ['stripe_id' => $invoice->customer]);
    $user = User::where('stripe_id', $invoice->customer)->first();

    if ($user) {
        Log::info('User found:', ['user_id' => $user->id, 'email' => $user->email]);

        // Find the subscription associated with the invoice
        Log::info('Attempting to find subscription with Stripe ID:', ['subscription_id' => $invoice->subscription]);
        $subscription = $user->subscriptions()->where('stripe_id', $invoice->subscription)->first();

        if ($subscription) {
            Log::info('Subscription found:', ['subscription_id' => $subscription->id]);

            // Get the price ID from the subscription
            $priceId = $subscription->stripe_price;
            Log::info('Price ID from subscription:', ['price_id' => $priceId]);

            // Find the corresponding pricing plan in your database
            Log::info('Attempting to find pricing plan with Stripe price ID:', ['price_id' => $priceId]);
            $pricingPlan = PricingPlan::where('stripe_price_id', $priceId)->first();

            if ($pricingPlan) {
                Log::info('Pricing plan found:', ['pricing_plan_id' => $pricingPlan->id]);

                // Update the user's credits and tokens
                Log::info('Updating user credits and tokens...', [
                    'current_credits' => $user->credits_left,
                    'current_tokens' => $user->tokens_left,
                    'credits_to_add' => $pricingPlan->images,
                    'tokens_to_add' => $pricingPlan->tokens,
                ]);
                $user->increment('credits_left', $pricingPlan->images);
                $user->increment('tokens_left', $pricingPlan->tokens);
                Log::info('User credits and tokens updated successfully.');

                // Log the renewal in your package history
                Log::info('Creating PackageHistory entry...', [
                    'user_id' => $user->id,
                    'package_id' => $pricingPlan->id,
                    'invoice' => $invoice->id,
                    'package_amount' => $invoice->amount_paid / 100,
                ]);
                PackageHistory::create([
                    'user_id' => $user->id,
                    'package_id' => $pricingPlan->id,
                    'invoice' => $invoice->id,
                    'package_amount' => $invoice->amount_paid / 100, // Convert cents to dollars
                    'created_at' => Carbon::now(),
                ]);
                Log::info('PackageHistory entry created successfully.');
            } else {
                Log::error('Pricing plan not found for Stripe price ID:', ['price_id' => $priceId]);
            }
        } else {
            Log::error('Subscription not found for Stripe subscription ID:', ['subscription_id' => $invoice->subscription]);
        }
    } else {
        Log::error('User not found for Stripe customer ID:', ['stripe_id' => $invoice->customer]);
    }

    Log::info('handleInvoicePaymentSucceeded completed.');
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
