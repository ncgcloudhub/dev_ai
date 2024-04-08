<?php

namespace App\Http\Controllers;

use App\Models\CustomTemplate;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
       
        // dd($templates_count);
        return view('admin.admin_dashboard', compact('user','templates_count','custom_templates_count','templates','custom_templates','usersByCountry','totalUsers','wordCountSum','allUsers'));
       
    }
}
