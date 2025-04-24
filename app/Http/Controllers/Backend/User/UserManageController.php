<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\PackageHistory;
use App\Models\Referral;
use App\Models\RequestModuleFeedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotification;
use App\Models\DalleImageGenerate as ModelsDalleImageGenerate;
use App\Models\blockCountry;
use App\Models\EmailSend;
use App\Models\UserActivityLog;
use App\Models\UserFeedback;
use App\Models\UserPageTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class UserManageController extends Controller
{
    public function ManageUser()
    {
        return view('backend.user.manage_user');
    }
  
    public function manageBlock()
    {
        $countries = blockCountry::latest()->get();
        return view('backend.user.block_manage_admin', compact('countries'));
    }

    public function storeBlock(Request $request)
    {
        $user_id = Auth::user()->id;
        $validatedData = $request->validate([
            'country_code' => 'required|string',
        ]);

        $code = blockCountry::create([
            'country_code' => $validatedData['country_code'],
            'user_id' => $user_id,
        ]);


        return redirect()->route('manage.block');
    }

    public function editCountry($id)
    {
        $countries = blockCountry::latest()->get();
        $country = blockCountry::findOrFail($id);
        return view('backend.user.block_edit_admin', compact('countries', 'country'));
    }


    public function updateCountry(Request $request)
    {

        $id = $request->id;

        blockCountry::findOrFail($id)->update([
            'country_code' => $request->country_code,
            'updated_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Country Block Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

        // end else 

    } // end method 
    


    public function countryDestroy($id)
    {
        $country = blockCountry::findOrFail($id);
        $country->delete();

        return redirect()->route('manage.block')->with('success', 'Country deleted successfully');
    }



    public function UpdateUserStatus(Request $request)
    {
        // Retrieve the User ID from the request
        $userId = $request->input('user_id');

        // Find the User by ID
        $user = User::find($userId);

        // Update the status (assuming 'status' is a field in your 'models_dalle_image_generates' table)
        if ($user->status == 'inactive') {
            $user->status = 'active';
            $user->save();
            return response()->json(['success' => true, 'message' => 'User status updated successfully']);
        } elseif ($user->status == 'active') {
            $user->status = 'inactive';
            $user->save();
            return response()->json(['success' => true, 'message' => 'User status updated successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    }

    

    public function UserDetails($id)
    {
        $user = User::findOrFail($id);
        $images = ModelsDalleImageGenerate::where('user_id', $id)->get();

        $logs = UserActivityLog::where('user_id', $id)
        ->latest()
        ->take(20)
        ->get();
       
        $time = UserPageTime::where('user_id', $id)
        ->latest()
        ->take(20)
        ->get();

        // Generate Azure Blob Storage URL for each image with SAS token
        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
        }

        return view('backend.user.user_details', compact('user','images', 'logs','time'));
    }

    public function UpdateUserStats(Request $request)
    {

        User::findOrFail($request->id)->update([
            'tokens_left' => $request->tokens_left,
            'credits_left' => $request->credits_left,
            'role' => $request->role,
        ]);

        // Optionally, you can return a response indicating success or redirect to a different page
        return redirect()->back()->with('success', 'User Stats updated Successfully');
    }

    // REFERRAL
    public function ManageReferral()
    {
        $referrals = Referral::with(['referrer', 'referralUser'])
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.referral.manage_refferal', compact('referrals'));
    }

    // Block User
    public function blockUser(Request $request, User $user)
    {
        // Update the block status based on the value from the form
        $user->block = $request->input('block') ? true : false;
        $user->save();
    
        return redirect()->back()->with('success', 'User block status updated successfully.');
    }

    public function bulkBlock(Request $request) {
        $users = User::whereIn('id', $request->user_ids)->get();
        foreach ($users as $user) {
            $user->block = !$user->block; // Toggle block status
            $user->save();
        }
        return redirect()->back()->with('success', 'Block status updated for selected users.');
    }

    public function bulkVerifyEmail(Request $request)
    {
        $userIds = $request->input('user_ids'); // Get the selected user IDs from the form

        // Get the users that need email verification
        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            // Only send a verification email if the user hasn't verified their email already
            if (!$user->email_verified_at) {
                // You can use Laravel's built-in notification system for email verification
                $user->sendEmailVerificationNotification(); // Assuming you're using Laravel's built-in notification for email verification
            }
        }

        // You may want to add a success message or redirect the user to a specific page
        return redirect()->back()->with('success', 'Email Verification sent to selected users.');
    }


    public function bulkStatusChange(Request $request)
    {
        // Ensure the request contains selected user IDs
        if ($request->has('user_ids')) {
            $userIds = $request->input('user_ids');
    
            // Loop through each selected user and toggle the status
            User::whereIn('id', $userIds)->get()->each(function ($user) {
                $user->status = ($user->status === 'active') ? 'inactive' : 'active';
                $user->save();
            });
    
            // Redirect with a success message
            return redirect()->back()->with('success', 'Status changed for selected users.');
        }
    
        // If no users are selected, return with an error message
        return redirect()->back()->with('error', 'No users selected for status change.');
    }
    

    public function packageHistory()
    {
        $packageGroupedByUser = PackageHistory::with(['user', 'package' => function($query) {
            $query->orderBy('created_at', 'desc')->first();
        }])
        ->whereHas('user') // Ensures the user relationship exists
        ->select('user_id', DB::raw('COUNT(*) as package_count'), DB::raw('MAX(created_at) as latest_package_date'))
        ->groupBy('user_id')
        ->get();

        return view('backend.user.package_history_user', compact('packageGroupedByUser'));
    }


    public function ModuleFeedbackRequest()
    {
        $feedbacks = RequestModuleFeedback::with('user')->orderby('id', 'asc')->get();

        return view('backend.user.user_feedback_request', compact('feedbacks'));
    }

    public function updateStatus(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:request_module_feedback,id',
        'status' => 'required|string'
    ]);

    $feedback = RequestModuleFeedback::find($request->id);
    $feedback->status = $request->status;
    $feedback->save();

    return response()->json(['success' => true]);
}


    // SEND EMAIL
    public function sendEmailForm()
    {   $users = User::all();
        return view('backend.user.send_email_form', compact('users'));
    }

    public function manageSendEmail()
    {
        // Retrieve all users
        $users = User::all();
    
        // Retrieve all sent emails (or you can limit the retrieval to a certain number or filter)
        $sentEmails = EmailSend::with('user') // If you have a relationship set up
            ->orderBy('created_at', 'desc') // Get the latest sent emails first
            ->get();
    
        // Pass both users and sent emails to the view
        return view('backend.user.manage_send_email', compact('users', 'sentEmails'));
    }
    

    public function sendEmail(Request $request)
    {
        Log::info($request->all());
    
        // Validate the request
        $request->validate([
            'user_id' => 'required|array', // Ensure user_id is validated as an array
            'user_id.*' => 'required|exists:users,id', // Ensure user IDs exist in the users table
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
    
        // Retrieve users' names and emails based on user IDs
        $users = User::whereIn('id', $request->user_id)->get(['name', 'email']);
    
        // Email details
        $details = [
            'subject' => $request->subject,
            'body' => $request->body
        ];
    
        // Send the email to all selected users
        foreach ($users as $user) {
            $details['body'] = "Hello {$user->name},<br><br>" . $request->body;
            Mail::to($user->email)->send(new UserNotification($details));
        }
    
        // Save a single record with all emails and user IDs
        EmailSend::create([
            'user_emails' => json_encode($users->pluck('email')->toArray()), // Store emails as JSON
            'user_ids' => json_encode($request->user_id), // Store user IDs as JSON
            'subject' => $request->subject,
            'body' => $request->body,
        ]);
    
        return back()->with('success', 'Emails sent and logged successfully!');
    }
    
    public function userfeedback()
    {
        $feedbacks = UserFeedback::with('user')->latest()->get();
        return view('admin.userfeedback.manage_feedback', compact('feedbacks'));
    }


    

}
