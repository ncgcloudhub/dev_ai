<?php

namespace App\Http\Controllers\Backend\FAQ;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function ManageFaq()
    {   
        $faqs = FAQ::latest()->get();
        return view('backend.faq.faq_manage', compact('faqs'));
    }

    public function AddFAQ()
    {
        return view('backend.pricing.add_pricing_plan');
    }

    public function StoreFAQ(Request $request)
    {
     
        $validatedData = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);
    
        $faq = FAQ::create([
            'question' => $validatedData['question'],
            'answer' => $validatedData['answer'],
        ]);
    
        
        return response()->json([
            'success' => true,
            'message' => 'FAQ Added Successfully',
            'faq' => $faq, // Optionally, you can return the created FAQ record
        ]);
        
    }

    public function EditPricing($slug)
    {
        $pricing_plan = PricingPlan::where('slug', $slug)->firstOrFail();

        return view('backend.pricing.edit_pricing_plan', compact('pricing_plan'));
    }

    public function UpdatePricing(Request $request, PricingPlan $pricingPlan)
{
    // dd($pricingPlan);
    $slug = Str::slug($request->title);

    $pricingPlan->update([
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

    $notification = [
        'message' => 'Pricing Plan Updated Successfully',
        'alert-type' => 'success'
    ];

    return redirect()->route('manage.pricing')->with($notification);
}
}
