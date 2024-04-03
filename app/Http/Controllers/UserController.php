<?php

namespace App\Http\Controllers;

use App\Models\CustomTemplate;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function UserDashboard(){
        $user = Auth::user();
        $templates_count = Template::count();
        $custom_templates_count = CustomTemplate::where('user_id', $user->id)->count();
        $templates = Template::orderby('total_word_generated', 'desc')->limit(5)->get();
        $custom_templates = CustomTemplate::where('user_id', $user->id)->limit(5)->get();

        $totalUsers = User::count();
        $usersByCountry = User::select('country', DB::raw('count(*) as total_users'))
        ->whereNotNull('country') // Exclude users with NULL country
        ->groupBy('country')
        ->get();
       
        // dd($templates_count);
        return view('user.user_dashboard', compact('user','templates_count','custom_templates_count','templates','custom_templates','usersByCountry','totalUsers'));
    }
}
