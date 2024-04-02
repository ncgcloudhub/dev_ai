<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function AllPackage(){

        return view('backend.subscription.all_package');
    }// End Method  

    public function BuySubscriptionPlan(){

        $id = Auth::user()->id;
        $user = User::find($id);
        return view('backend.subscription.package_invoice', compact('user'));

    }// End Method  

    public function StoreSubscriptionPlan(Request $request){
       
        $id = Auth::user()->id;

      SubscriptionPlan::insert([

        'user_id' => $id,
        'package_name' => 'Business',
        'package_images' => '10',
        'invoice' => 'TRI'.mt_rand(10000000,99999999),
        'package_amount' => '20',
        'created_at' => Carbon::now(), 
      ]);

      // Image Increment
    User::where('id', $id)->update([
        'images_generated' => DB::raw('images_generated + 10'),
    ]);

       $notification = array(
            'message' => 'You have purchase Basic Package Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.package')->with($notification); 
    }// End Method 
}
