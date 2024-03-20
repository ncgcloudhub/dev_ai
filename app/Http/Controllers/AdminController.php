<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function AdminDashboard(){
        $admin_name = Auth::user()->name;
        return view('admin.admin_dashboard', compact('admin_name'));
    }
}
