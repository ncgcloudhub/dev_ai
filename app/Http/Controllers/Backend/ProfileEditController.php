<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ProfileEditController extends Controller
{
    
    public function ProfileEdit(){

        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        return view('backend.profile.profile_edit', compact('user'));
    
    }

    public function ProfileUpdate (Request $request){

        $user_id = $request->profile_id;

       
        User::where('id', $user_id)->update([
            
          'name' => $request->name,
          'username' => $request->username,
          'address' => $request->address,
          'phone' => $request->phone,
          'updated_at' => Carbon::now(),   
  
        ]);
  
  
         $notification = array(
              'message' => 'Settings Changed Successfully',
              'alert-type' => 'success'
          );
  
          return redirect()->back()->with($notification);
  
      }

}
