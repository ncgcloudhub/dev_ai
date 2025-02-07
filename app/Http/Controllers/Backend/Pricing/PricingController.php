<?php

namespace App\Http\Controllers\Backend\Pricing;

use App\Http\Controllers\Controller;
use App\Models\AISettings;
use App\Models\PricingPlan;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PricingController extends Controller
{
    public function ManagePricingPlan()
    {
        $pricingPlans = PricingPlan::orderBy('id', 'asc')->get();
        $highestDiscount = PricingPlan::where('package_type', 'yearly')->where('discount_type', 'percentage')->max('discount');


        $monthlyPlans = $pricingPlans->filter(function ($plan) {
            return $plan->package_type === 'monthly';
        });

        $yearlyPlans = $pricingPlans->filter(function ($plan) {
            return $plan->package_type === 'yearly';
        });

        $totalTemplates = Template::count();
        return view('backend.pricing.pricing_manage', compact('monthlyPlans', 'totalTemplates', 'yearlyPlans','highestDiscount'));
    }

    public function addPricingPlan()
    {
        $totalTemplates = Template::count();
        $models = AISettings::where('status', 1)->get();
        return view('backend.pricing.add_pricing_plan', compact('totalTemplates','models'));
    }

    public function StorePricingPlan(Request $request)
    {

        $gptModels = $request->input('open_id_model', []);
        if (!is_array($gptModels)) {
            $gptModels = [$gptModels];
        }
        // Convert array to string
        $gptModelsImplode = implode(', ', $gptModels);

        // Assuming $request is an instance of Illuminate\Http\Request
        $title = $request->input('title');
        $packageType = $request->input('package_type');

        // Generate slugs for each part
        $titleSlug = Str::slug($title);
        $packageTypeSlug = Str::slug($packageType);

        // Combine the slugs with an underscore
        $combinedSlug = $titleSlug . '_' . $packageTypeSlug;

        // Calculate discounted price based on discount type
        $price = $request->input('price');
        $discount = $request->input('discount');
        $discountType = $request->input('discount_type');
        $discountedPrice = $price; // Default to price if no discount or invalid input

        if ($discountType === 'percentage' && $discount > 0 && $discount <= 100) {
            $discountedPrice = $price - ($price * ($discount / 100));
        } elseif ($discountType === 'flat' && $discount > 0) {
            $discountedPrice = $price - $discount;
        }

        // dd($request);
        $pricingPlan = PricingPlan::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => $combinedSlug,
            'open_id_model' => $gptModelsImplode,
            'package_type' => $request->package_type,
            'discount' => $request->discount,
            'discount_type' => $request->discount_type,
            'price' => $price,
            'discounted_price' => $discountedPrice, // Use calculated discounted price
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

    public function EditPricing($slug)
    {
        $pricing_plan = PricingPlan::where('slug', $slug)->firstOrFail();
        $models = AISettings::latest()->get();

        return view('backend.pricing.edit_pricing_plan', compact('pricing_plan', 'models'));
    }

    public function UpdatePricing(Request $request, PricingPlan $pricingPlan)
    {

        $gptModels = $request->input('open_id_model', []);
        if (!is_array($gptModels)) {
            $gptModels = [$gptModels];
        }
        // Convert array to string
        $gptModelsImplode = implode(', ', $gptModels);

        // Generate the slug by combining the title and package type
        $titleSlug = Str::slug($request->title);
        $packageTypeSlug = Str::slug($request->package_type);
        $slug = $titleSlug . '_' . $packageTypeSlug;

        // Calculate discounted price based on discount type
        $price = $request->input('price');
        $discount = $request->input('discount');
        $discountType = $request->input('discount_type');
        $discountedPrice = $price; // Default to price if no discount or invalid input

        if ($discountType === 'percentage' && $discount > 0 && $discount <= 100) {
            $discountedPrice = $price - ($price * ($discount / 100));
        } elseif ($discountType === 'flat' && $discount > 0) {
            $discountedPrice = $price - $discount;
        }

        $pricingPlan->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => $slug,
            'open_id_model' => $gptModelsImplode,
            'package_type' => $request->package_type,
            'price' => $price,
            'discount' => $request->discount,
            'discount_type' => $request->discount_type,
            'discounted_price' => $discountedPrice, // Use calculated discounted price
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

    public function destroy($slug)
    {
        $item = PricingPlan::where('slug', $slug)->firstOrFail();
        $item->delete();

        return redirect()->route('manage.pricing')->with('success', 'Plan deleted successfully.');
    }
}
