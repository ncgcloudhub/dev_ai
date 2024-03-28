<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function UserDashboard(){
        $user_name = Auth::user()->name;
        return view('user.user_dashboard', compact('user_name'));
    }
}
