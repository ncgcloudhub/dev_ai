<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StatsController extends Controller
{
    public function StatsView()
{
    $totalUsers = User::count();
    $verifiedEmails = User::whereNotNull('email_verified_at')->count();
    $unverifiedEmails = $totalUsers - $verifiedEmails;
    $activeUsers = User::where('status', 'active')->count(); // Assuming you have a 'status' column
    $inactiveUsers = $totalUsers - $activeUsers;

    $usersByCountry = User::select('country', DB::raw('count(*) as count'))
                          ->groupBy('country')
                          ->pluck('count', 'country');

                          $totalCreditsUsed = User::sum('credits_used');
                          $totalTokensUsed = User::sum('tokens_used');
                          $totalImagesGenerated = User::sum('images_generated');
                      
    return view('backend.user.stats_view', compact(
        'totalUsers', 
        'verifiedEmails', 
        'unverifiedEmails', 
        'activeUsers', 
        'inactiveUsers', 
        'usersByCountry', 
        'totalCreditsUsed', 
        'totalTokensUsed', 
        'totalImagesGenerated'
    ));
}

}
