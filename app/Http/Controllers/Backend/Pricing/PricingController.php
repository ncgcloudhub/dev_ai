<?php

namespace App\Http\Controllers\Backend\Pricing;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PricingController extends Controller
{
    public function ManagePricingPlan()
    {   
        $pricingPlans = PricingPlan::latest()->get();
        return view('backend.pricing.pricing_manage', compact('pricingPlans'));
    }

    public function addPricingPlan()
    {
        return view('backend.pricing.add_pricing_plan');
    }

    public function StorePricingPlan(Request $request)
    {
        $slug = Str::slug('title');
        // dd($request);
        $pricingPlan = PricingPlan::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => $slug,
            'open_id_model' => $request->open_id_model,
            'package_type' => $request->package_type,
            'price' => $request->price,
            'discounted_price' => $request->discounted_price,
            'tokens' => $request->tokens,
            '71_ai_templates' => $request->has('71_ai_templates') ? true : false,
            'ai_chat' => $request->has('ai_chat') ? true : false,
            'ai_code' => $request->has('ai_code') ? true : false,
            'text_to_speech' => $request->has('text_to_speech') ? true : false,
            'custom_templates' => $request->has('custom_templates') ? true : false,
            'ai_blog_wizards' => $request->has('ai_blog_wizards') ? true : false,
            'images' => $request->images,
            'ai_images' => $request->has('ai_images') ? true : false,
            'stable_diffusion' => $request->has('stable_diffusion') ? true : false,
            'speech_to_text' => $request->speech_to_text,
            'live_support' => $request->has('live_support') ? true : false,
            'free_support' => $request->has('free_support') ? true : false,
            'active' => $request->active ?? 'inactive', // Assuming inactive if not provided
            'popular' => $request->popular ?? 'no', // Assuming no if not provided
            'additional_features' => $request->additional_features,
        ]);
        
        $notification = array(
            'message' => 'Pricing Plan Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
        
    }

}
