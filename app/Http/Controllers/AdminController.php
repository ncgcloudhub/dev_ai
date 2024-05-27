<?php

namespace App\Http\Controllers;

use App\Models\CustomTemplate;
use App\Models\Session;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard(){
        $user = Auth::user();
        $templates_count = Template::count();
        $custom_templates_count = CustomTemplate::count();
        $templates = Template::orderby('total_word_generated', 'desc')->limit(5)->get();
        $custom_templates = CustomTemplate::limit(5)->get();

        $wordCountSum = CustomTemplate::sum('total_word_generated');
        
        $totalUsers = User::where('role', 'user')->count();
        $allUsers = User::where('role', 'user')->orderBy('id', 'desc')->get();

        $usersByCountry = User::select('country', DB::raw('count(*) as total_users'))
        ->whereNotNull('country') // Exclude users with NULL country
        ->groupBy('country')
        ->get();

        $userId = auth()->id(); // Get the authenticated user's ID
        $sessions = Session::with('messages') // Eager load the related messages
                        ->where('user_id', $userId)
                        ->get();
    
       
        // dd($templates_count);
        return view('admin.admin_dashboard', compact('user','templates_count','custom_templates_count','templates','custom_templates','usersByCountry','totalUsers','wordCountSum','allUsers','sessions'));
       
    }
 
    public function showChangePasswordForm(User $user)
    {
        // Pass the user data to the view
        return view('backend.user.user_password', compact('user'));
    }

    public function changeUserPassword(Request $request, User $user)
    {
        // Validate the form data
        $request->validate([
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        // Redirect back to the form with success message
        return redirect()->route('admin.users.changePassword.view', ['user' => $user->id])
            ->with('success', 'Password updated successfully.');
    }

    public function destroy(User $user)
    {
        // Delete the user
        $user->delete();

        // Optionally, you can redirect the user to a specific page after deletion
        return redirect()->route('manage.user')->with('success', 'User deleted successfully.');
    }
}
