<?php

namespace App\Http\Controllers\Backend\FAQ;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\PricingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FAQController extends Controller
{
    public function ManageFaq()
    {
        $faqs = FAQ::latest()->get();
        return view('backend.faq.faq_manage', compact('faqs'));
    }

    // public function AddFAQ()
    // {
    //     return view('backend.pricing.add_pricing_plan');
    // }

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


    // Calender
    public function calender()
    {
        return view('admin.calender.apps-calendar');
    }
}
