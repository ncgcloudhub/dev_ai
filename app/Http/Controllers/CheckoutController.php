<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
                'success_url' => route('user.dashboard'),
                'cancel_url' => route('user.dashboard'),
            ]);
    }
}
