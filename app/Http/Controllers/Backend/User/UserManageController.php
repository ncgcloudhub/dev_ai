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
use App\Models\EmailSend;
use Illuminate\Support\Facades\Log;

class UserManageController extends Controller
{
    public function ManageUser()
    {
        return view('backend.user.manage_user');
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
        return view('backend.user.user_details', compact('user'));
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

    public function packageHistory()
    {
        // $packageGroupedByUser = PackageHistory::with('user') // Assumes a 'user' relationship exists
        //     ->select('user_id', DB::raw('COUNT(*) as package_count'))
        //     ->groupBy('user_id')
        //     ->get();

            $packageGroupedByUser = PackageHistory::with(['user', 'package' => function($query) {
                $query->orderBy('created_at', 'desc')->first();
            }])
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
    



    

}
