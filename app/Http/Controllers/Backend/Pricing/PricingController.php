<?php

namespace App\Http\Controllers\Backend\Pricing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function ManagePricingPlan()
    {
        return view('backend.pricing.pricing_manage');
    }

    public function addPricingPlan()
    {
        return view('backend.pricing.add_pricing_plan');
    }
}
