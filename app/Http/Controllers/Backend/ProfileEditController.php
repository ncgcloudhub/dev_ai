<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;
use Stripe\Invoice as StripeInvoice;

class ProfileEditController extends Controller
{
    public function ProfileEdit(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
    
        Stripe::setApiKey(config('services.stripe.secret'));
    
        $stripeCustomerId = $user->stripe_id;
    
        $subscriptions = collect(); // default as collection
        $invoices = collect();
    
        if ($stripeCustomerId) {
            $stripeSubscriptions = StripeSubscription::all([
                'customer' => $stripeCustomerId,
                'status' => 'all',
            ]);
    
            $subscriptions = collect($stripeSubscriptions->data);
    
            $stripeInvoices = StripeInvoice::all([
                'customer' => $stripeCustomerId,
            ]);
    
            $invoices = collect($stripeInvoices->data);
        }
    
        return view('backend.profile.profile_edit', compact('user', 'subscriptions', 'invoices'));
    }

    public function ProfileUpdate (Request $request){

        $user_id = $request->profile_id;

       
        User::where('id', $user_id)->update([
            
          'name' => $request->name,
          'username' => $request->username,
          'address' => $request->address,
          'phone' => $request->phone,
          'country' => $request->country,
          'updated_at' => Carbon::now(),   
  
        ]);
  
  
         $notification = array(
              'message' => 'Settings Changed Successfully',
              'alert-type' => 'success'
          );
  
          return redirect()->back()->with($notification);
  
      }

      public function ProfilePhotoUpdate(Request $request)
      {

        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
    
        if ($request->hasFile('profile_photo')) {
            
            // Delete the existing profile photo
            if ($user && $user->photo) {
                unlink(public_path('backend/uploads/user/' . $user->photo));
            }
    
            // Upload the new profile photo
            $photo = $request->file('profile_photo');
            $photoName = time() . '-' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move('backend/uploads/user', $photoName);
    
            // Update the user's photo attribute in the database
            $user->photo = $photoName;
            $user->save();
        }
    
  
          return redirect()->back()->with('success', 'Profile Photo Updated Successfully');
      }

     
}
