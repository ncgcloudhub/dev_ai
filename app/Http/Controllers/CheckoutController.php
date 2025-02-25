<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $pricingPlanId, $plan)
    {
              return $request->user()
                ->newSubscription('prod_Rpzyi6JwMveusr', $plan)
                ->checkout([
                    'success_url' => route('success'),
                    'cancel_url' => route('user.dashboard'),
                ]);
    }
}
