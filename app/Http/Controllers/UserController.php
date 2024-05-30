<?php

namespace App\Http\Controllers;

use App\Models\CustomTemplate;
use App\Models\Template;
use App\Models\User;
use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DalleImageGenerate;
use App\Models\Session;

class UserController extends Controller
{
    public function UserDashboard()
    {
        $user = Auth::user();
        $templates_count = Template::count();
        $chatbot_count = Expert::count();
        $custom_templates_count = CustomTemplate::where('user_id', $user->id)->count();
        $templates = Template::orderby('total_word_generated', 'desc')->limit(5)->get();
        $custom_templates = CustomTemplate::where('user_id', $user->id)->limit(5)->get();
        $images = DalleImageGenerate::where('user_id', $user->id)->orderBy('id', 'desc')->limit(12)->get();

        $totalUsers = User::count();
        $usersByCountry = User::select('country', DB::raw('count(*) as total_users'))
            ->whereNotNull('country') // Exclude users with NULL country
            ->groupBy('country')
            ->get();

        $userId = auth()->id(); // Get the authenticated user's ID
        $sessions = Session::with('messages') // Eager load the related messages
            ->where('user_id', $userId)
            ->get();

        // Generate Azure Blob Storage URL for each image with SAS token
        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
        }

        // dd($templates_count);
        return view('user.user_dashboard', compact('user', 'templates_count', 'sessions', 'custom_templates_count', 'chatbot_count', 'templates', 'custom_templates', 'usersByCountry', 'totalUsers', 'images'));
    }
}
