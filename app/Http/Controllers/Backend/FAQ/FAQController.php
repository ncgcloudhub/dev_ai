<?php

namespace App\Http\Controllers\Backend\FAQ;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\Jokes;
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

    // In your controller
        public function fetchRandomJoke($category) {
            $joke = Jokes::where('category', $category)->inRandomOrder()->first();
            return response()->json(['joke' => $joke->content ?? 'No jokes available in this category.']);
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

    // app/Http/Controllers/FAQController.php
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        // Find the FAQ by id and update it
        $faq = FAQ::findOrFail($id);
        $faq->update([
            'question' => $validatedData['question'],
            'answer' => $validatedData['answer'],
        ]);

        // Redirect back to the manage FAQ page with a success message
        return redirect()->route('manage.faq')->with('success', 'FAQ updated successfully');
    }

    // app/Http/Controllers/FAQController.php
    public function destroy($id)
    {
        // Find the FAQ by id and delete it
        $faq = FAQ::findOrFail($id);
        $faq->delete();

        // Redirect back to the manage FAQ page with a success message
        return redirect()->route('manage.faq')->with('success', 'FAQ deleted successfully');
    }




    // Calender
    public function calender()
    {
        return view('admin.calender.apps-calendar');
    }
}
