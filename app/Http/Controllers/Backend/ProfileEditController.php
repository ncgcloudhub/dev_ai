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
use Stripe\Subscription;
use Stripe\Invoice;

class ProfileEditController extends Controller
{
    
    public function ProfileEdit(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);

        // STRIPE DASHBOARD
        // Set the Stripe API key from the .env file
        Stripe::setApiKey(config('services.stripe.secret'));

        // Get the authenticated user
        $user = $request->user();

        // Fetch the user's Stripe customer ID
        $stripeCustomerId = $user->stripe_id; // Ensure this field exists in your users table

        // Fetch subscriptions
        $subscriptions = [];
        if ($stripeCustomerId) {
            $subscriptions = Subscription::all([
                'customer' => $stripeCustomerId,
                'status' => 'all', // or 'active', 'past_due', 'canceled', etc.
            ]);
        }

        // Fetch invoices
        $invoices = [];
        if ($stripeCustomerId) {
            $invoices = Invoice::all([
                'customer' => $stripeCustomerId,
            ]);
        }
    
        return view('backend.profile.profile_edit', compact('user','subscriptions','invoices'));
    }

    public function download($invoiceId)
{
    // Set the Stripe API key from the .env file
    Stripe::setApiKey(config('services.stripe.secret'));

    // Fetch the invoice
    $invoice = Invoice::retrieve($invoiceId);

    // Get the invoice PDF URL
    $invoicePdfUrl = $invoice->invoice_pdf;

    // Redirect the user to the PDF URL
    return redirect($invoicePdfUrl);
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
