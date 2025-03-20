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
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response('Webhook error', 400);
        }

        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;
        }

        return response('Webhook handled', 200);
    }

    protected function handleInvoicePaymentSucceeded($invoice)
    {
        // Find the user associated with the Stripe customer ID
        $user = User::where('stripe_id', $invoice->customer)->first();
    
        if ($user) {
            // Find the subscription associated with the invoice
            $subscription = $user->subscriptions()->where('stripe_id', $invoice->subscription)->first();
    
            if ($subscription) {
                // Get the price ID from the subscription
                $priceId = $subscription->stripe_price;
    
                // Find the corresponding pricing plan in your database
                $pricingPlan = PricingPlan::where('stripe_price_id', $priceId)->first();
    
                if ($pricingPlan) {
                    // Update the user's credits and tokens
                    $user->increment('credits_left', $pricingPlan->images);
                    $user->increment('tokens_left', $pricingPlan->tokens);
    
                    // Log the renewal in your package history
                    PackageHistory::create([
                        'user_id' => $user->id,
                        'package_id' => $pricingPlan->id,
                        'invoice' => $invoice->id,
                        'package_amount' => $invoice->amount_paid / 100, // Convert cents to dollars
                        'created_at' => Carbon::now(),
                    ]);
                }
            }
        }
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
