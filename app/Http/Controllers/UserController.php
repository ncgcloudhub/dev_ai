<?php

namespace App\Http\Controllers;

use App\Models\CustomTemplate;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function UserDashboard(){
        $user = Auth::user();
        $templates_count = Template::count();
        $custom_templates_count = CustomTemplate::where('user_id', $user->id)->count();
        $templates = Template::orderby('total_word_generated', 'desc')->limit(5)->get();
        $custom_templates = CustomTemplate::where('user_id', $user->id)->limit(5)->get();
       
        // dd($templates_count);
        return view('user.user_dashboard', compact('user','templates_count','custom_templates_count','templates','custom_templates'));
    }
}
