<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\PackageHistory;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
            'role' => $request->role,
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

    // Block User
    public function blockUser(Request $request, User $user)
    {
        // Update the block status based on the value from the form
        $user->block = $request->input('block') ? true : false;
        $user->save();
    
        return redirect()->back()->with('success', 'User block status updated successfully.');
    }

    public function packageHistory()
    {
        // $packageGroupedByUser = PackageHistory::with('user') // Assumes a 'user' relationship exists
        //     ->select('user_id', DB::raw('COUNT(*) as package_count'))
        //     ->groupBy('user_id')
        //     ->get();

            $packageGroupedByUser = PackageHistory::with(['user', 'package' => function($query) {
                $query->orderBy('created_at', 'desc')->first();
            }])
            ->select('user_id', DB::raw('COUNT(*) as package_count'), DB::raw('MAX(created_at) as latest_package_date'))
            ->groupBy('user_id')
            ->get();

        return view('backend.user.package_history_user', compact('packageGroupedByUser'));
    }
    

}
