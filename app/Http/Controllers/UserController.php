<?php

namespace App\Http\Controllers;

use App\Exports\AllUserExport1;
use App\Exports\AllUsersExport;
use App\Models\CustomTemplate;
use App\Models\Template;
use App\Models\User;
use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DalleImageGenerate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

    // User Export ALL
    public function export()
    {
        return Excel::download(new AllUsersExport, 'all_users.xlsx');
    }
    public function export1()
    {
        return Excel::download(new AllUserExport1, 'all_users1.xlsx');
    }

    // Resend email verification
    public function sendVerificationEmail(User $user)
    {
        try {
            if ($user->hasVerifiedEmail()) {
                return redirect()->back()->with('success', 'Email is already verified.');
            }
    
            $user->sendEmailVerificationNotification();
    
            return redirect()->back()->with('success', 'Verification email sent.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send verification email.');
        }
    }

     // SELECT USER MODEL GLOBAL
     public function selectModel(Request $request)
     {   
         $user_id = Auth::user()->id;
         $user = User::findOrFail($user_id);
        
         if ($user->tokens_left < 5000) {
            $user->selected_model = '4o-mini';
            $user->save();

            log_activity('Model Changed to 4o-mini.');
            
            return redirect()->back()->with('error', 'You do not have enough tokens to select this model. The model has been set to 4o-mini.');
        } else {
            // Otherwise, save the model selected by the user
            $user->selected_model = trim($request->input('aiModel'));
            log_activity('Model Changed to '. $request->input('aiModel'));
        }

         $user->save();
 
         return redirect()->back()->with('success', 'Model Updated Successfully');
     }

    //  Tour Status
    public function updateTourStatus(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $user->has_seen_tour = $request->has_seen_tour;
        $user->save();

        Log::info('Tour status updated for user:', ['user_id' => $user->id, 'status' => $request->has_seen_tour]);
        

        return response()->json(['status' => 'success']);
    }

 


}
