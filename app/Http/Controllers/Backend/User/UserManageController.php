<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManageController extends Controller
{
    public function ManageUser()
    {
        $users = User::where('role', 'user')->orderBy('id', 'desc')->get();

        return view('backend.user.manage_user', compact('users'));
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

}
