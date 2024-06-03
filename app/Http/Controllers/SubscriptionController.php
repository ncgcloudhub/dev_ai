<?php

namespace App\Http\Controllers;

use App\Models\PackageHistory;
use App\Models\PricingPlan;
use App\Models\SubscriptionPlan;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function AllPackage(){

        $pricingPlans = PricingPlan::latest()->get();
        $totalTemplates = Template::count();

         // Get the authenticated user
         $user = Auth::user();
        // Get the last package bought by the user
        $lastPackageHistory = PackageHistory::where('user_id', $user->id)
            ->latest()
            ->first();
        
        $lastPackageId = $lastPackageHistory ? $lastPackageHistory->package_id : null;

        return view('backend.subscription.all_package', compact('pricingPlans','lastPackageId','totalTemplates') );
    }// End Method  

    public function purchase($pricingPlanId)
    {
        $pricingPlan = PricingPlan::findOrFail($pricingPlanId);
        $id = Auth::user()->id;
        $user = User::find($id);

        return view('backend.subscription.package_invoice', compact('pricingPlan','user'));
    }

    public function StoreSubscriptionPlan(Request $request){
       
        $user_id = Auth::user()->id;
        $pricingPlan = PricingPlan::findOrFail($request->pricing_plan_id);

      PackageHistory::insert([
        'user_id' => $user_id,
        'package_id' => $pricingPlan->id,
        'invoice' => 'CC'.mt_rand(10000000,99999999),
        'package_amount' => $pricingPlan->price,
        'created_at' => Carbon::now(), 
      ]);

      // Image Increment
    User::where('id', $user_id)->update([
        'images_left' => DB::raw('images_left + ' . $pricingPlan->images),
        'words_left' => DB::raw('words_left + ' . $pricingPlan->tokens)
    ]);

       $notification = array(
            'message' => 'Your package has been successfully purchased',
            'alert-type' => 'success'
        );

        return redirect()->route('all.package')->with($notification); 
    }// End Method 
}
