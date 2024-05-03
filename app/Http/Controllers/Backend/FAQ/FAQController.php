<?php

namespace App\Http\Controllers\Backend\FAQ;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\PricingPlan;
use App\Models\PrivacyPolicy;
use App\Models\TermsConditions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    
       
        return redirect()->route('manage.faq');
        
    }

    // PRIVACY POLICY
    public function ManagePrivacyPolicy()
    {
        $privacy_policy = PrivacyPolicy::latest()->get();
        return view('backend.privacy_policy.manage_privacy_policy', compact('privacy_policy'));
    }

    public function StorePrivacyPolicy(Request $request)
    {

        $privacy_policy = PrivacyPolicy::create([
            'details' =>$request->details
        ]);
    
        return redirect()->back();
        
    }

    // TERMS & CONDITIONS
    public function ManageTermsCondition()
    {
        $terms_conditions = TermsConditions::latest()->get();
        return view('backend.terms_conditions.manage_terms_conditions', compact('terms_conditions'));
    }

    public function StoreTermsCondition(Request $request)
    {

        $privacy_policy = TermsConditions::create([
            'details' =>$request->details
        ]);
    
        return redirect()->back();
        
    }

}
