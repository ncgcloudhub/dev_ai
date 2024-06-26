<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;

class UserManageController extends Controller
{
    public function ManageUser()
    {
        return view('backend.user.manage_user');
    }

    public function UpdateUserStatus(Request $request)
    {
        // Retrieve the User ID from the request
        $userId = $request->input('user_id');

        // Find the User by ID
        $user = User::find($userId);

        // Update the status (assuming 'status' is a field in your 'models_dalle_image_generates' table)
        if ($user->status == 'inactive') {
            $user->status = 'active';
            $user->save();
            return response()->json(['success' => true, 'message' => 'User status updated successfully']);
        } elseif ($user->status == 'active') {
            $user->status = 'inactive';
            $user->save();
            return response()->json(['success' => true, 'message' => 'User status updated successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    }

    public function UserDetails($id)
    {
        $user = User::findOrFail($id);
        return view('backend.user.user_details', compact('user'));
    }

    public function UpdateUserStats(Request $request)
    {

        User::findOrFail($request->id)->update([
            'tokens_left' => $request->tokens_left,
            'credits_left' => $request->credits_left,
        ]);

        // Optionally, you can return a response indicating success or redirect to a different page
        return redirect()->back()->with('success', 'User Stats updated Successfully');
    }


    // REFERRAL
    public function ManageReferral()
    {
        $referrals = Referral::with(['referrer', 'referralUser'])
        ->orderBy('id', 'desc')
        ->get();

        return view('backend.referral.manage_refferal', compact('referrals'));
    }
}
