<?php

namespace App\Http\Controllers;

use App\Models\CustomTemplate;
use App\Models\DalleImageGenerate;
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

        $usersByCountry = User::select('country', DB::raw('count(*) as total_users'))
        ->whereNotNull('country') // Exclude users with NULL country
        ->groupBy('country')
        ->get();

        $userId = auth()->id(); // Get the authenticated user's ID
        $sessions = Session::with('messages') // Eager load the related messages
                        ->where('user_id', $userId)
                        ->get();

         // Fetch images with their likes, order by the number of likes
         $images = DalleImageGenerate::withCount('likes')
         ->whereHas('likes') // Filter images that have at least one like
         ->orderByDesc('likes_count') // Order by the number of likes in descending order
         ->get();

        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
        }

        // Calculate the total number of likes
        $totalLikes = $images->sum('likes_count');
       
        // dd($templates_count);
        return view('admin.admin_dashboard', compact('user','templates_count','custom_templates_count','templates','custom_templates','usersByCountry','totalUsers','wordCountSum', 'sessions','images','totalLikes'));
       
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
