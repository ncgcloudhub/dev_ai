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

class ProfileEditController extends Controller
{
    
    public function ProfileEdit()
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $packageHistory = $user->packageHistory()->with('package')->get();
        
        $freePricingPlan = null;
        $daysUntilNextReset = null;
        
        if ($packageHistory->isEmpty()) {
            // Free plan case
            $freePricingPlan = PricingPlan::where('title', 'Free')->first();
            
            // Calculate the next reset date for free plan
            $now = Carbon::now();
            $registrationDate = $user->created_at;
            $nextResetDate = $registrationDate->copy()->addMonths($registrationDate->diffInMonths($now) + 1);
            $daysUntilNextReset = $now->diffInDays($nextResetDate);
        } else {
            // Paid plan case, handle only the last paid package
            $firstPaidPackage = $packageHistory->last();
            
            if ($firstPaidPackage) {
                // Calculate the next reset date for the first paid package
                $now = Carbon::now();
                $startDate = $firstPaidPackage->created_at;
                $nextResetDate = $startDate->copy()->addMonths($startDate->diffInMonths($now) + 1);
                $daysUntilNextReset = $now->diffInDays($nextResetDate);
            }
        }
    
        return view('backend.profile.profile_edit', compact('user', 'packageHistory', 'freePricingPlan', 'daysUntilNextReset'));
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
