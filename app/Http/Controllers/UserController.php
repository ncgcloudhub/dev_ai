<?php

namespace App\Http\Controllers;

use App\Exports\AllUserExport1;
use App\Exports\AllUsersExport;
use App\Models\AISettings;
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
use App\Models\UserPageTime;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\EducationTools;
use App\Models\PromptLibrary;
use App\Models\UserFeedback;
use App\Models\UserMonthlyUsage;
use Carbon\Carbon;

class UserController extends Controller
{
    public function UserDashboard()
    {
        logActivity('Dashboard', 'accessed Dashboard');
        $user = Auth::user();
    
        $userId = auth()->id(); // Get the authenticated user's ID

        $eduTools = EducationTools::limit(5)->get();

        foreach ($eduTools as $image) {
            $image->image = config('filesystems.disks.azure.url') 
                . config('filesystems.disks.azure.container') 
                . '/' . $image->image;
        }
        
        $prompts = PromptLibrary::inRandomOrder()
            ->limit(11)->get();
       
        $aiContentCreator = Template::inRandomOrder()
            ->limit(6)->get();

        return view('user.user_dashboard_1', compact('user','eduTools','prompts','aiContentCreator'));
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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $selectedModel = trim($request->input('aiModel'));

        // Admin can access all models
        if ($user->hasRole('admin')) {
            $allowedModels = AISettings::pluck('openaimodel')->filter()->unique()->toArray();
        } else {
            // Regular user: fetch their package's allowed models
            $packageHistory = $user->packageHistory()
                                ->latest()
                                ->with('package') // <- using 'package' relationship
                                ->first();

            if (!$packageHistory || !$packageHistory->package_id || empty($packageHistory->package->open_id_model)) {
                return redirect()->back()->with('error', 'No valid package found. Cannot change AI model.');
            }
            
            $allowedModels = array_map('trim', explode(',', $packageHistory->package->open_id_model));
        }

        if (!in_array($selectedModel, $allowedModels)) {
            return redirect()->back()->with('error', 'Invalid AI model selected.');
        }

        if ($user->tokens_left < 5000) {
            $user->selected_model = 'gpt-4o-mini';
            $user->save();

            log_activity('Model Changed to gpt-4o-mini.');

            return redirect()->back()->with('error', 'You do not have enough tokens to select this model. The model has been set to gpt-4o-mini.');
        }

        // Save the model
        $user->selected_model = $selectedModel;
        $user->save();

        log_activity('Model Changed to ' . $selectedModel);

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

    public function saveSeenTourSteps(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $user->tour_progress = $request->input('seenTourSteps', []);
        $user->save();
        return response()->json(['success' => true]);
    }

    public function saveTime(Request $request)
    {
        Log::info('Save time method triggered', ['request' => $request->all()]);
        // Validate the incoming data
        $data = $request->validate([
            'time_spent' => 'array', // Expect an array of pages and time
            'time_spent.*.url' => 'required|string',
            'time_spent.*.time_spent' => 'required|numeric|min:0',
        ]);

        Log::info('Validated data', ['data' => $data]);

        $userId = auth()->id(); // Get the logged-in user ID

        foreach ($data['time_spent'] as $pageTime) {
            // Find existing record for the same user and page (without date filter)
            $existingRecord = UserPageTime::where('user_id', $userId)
                ->where('page_url', $pageTime['url'])
                ->first();

            if ($existingRecord) {
                // If record exists, update the time spent by adding the new time
                $existingRecord->update([
                    'time_spent' => $existingRecord->time_spent + $pageTime['time_spent'],
                ]);
            } else {
                // If no record exists, create a new one
                UserPageTime::create([
                    'user_id' => $userId,
                    'page_url' => $pageTime['url'],
                    'time_spent' => $pageTime['time_spent'],
                    'created_at' => now(),
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    // Fetch Monthly Usage
    public function getUserMonthlyUsage()
    {
        $user_id = Auth::id();

        $data = UserMonthlyUsage::where('user_id', $user_id)
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Prepare labels & data
        $monthlyLabels = [];
        $tokensUsed = [];
        $creditsUsed = [];

        foreach ($data as $entry) {
            $monthName = date("M", mktime(0, 0, 0, $entry->month, 1)); // Convert month number to short name (Jan, Feb, etc.)
            $monthlyLabels[] = $monthName;
            $tokensUsed[] = $entry->tokens_used;
            $creditsUsed[] = $entry->credits_used;
        }

        return response()->json([
            'labels' => $monthlyLabels,
            'tokens' => $tokensUsed,
            'credits' => $creditsUsed,
        ]);
    }

    // Fetch daily/Monthly Usage Zoomable
    public function getUserUsageTimeseries()
    {
        $user_id = Auth::id();
    
        $data = UserMonthlyUsage::where('user_id', $user_id)
            ->whereNotNull('day')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->orderBy('day', 'asc')
            ->get();
    
        $timeseriesData = [];
        
        foreach ($data as $entry) {
            $timeseriesData[] = [
                'date' => Carbon::create($entry->year, $entry->month, $entry->day)->format('Y-m-d'),
                'value' => $entry->tokens_used // or credits_used
            ];
        }
    
        Log::info('Timeseries Data:', $timeseriesData);
        return response()->json($timeseriesData);
    }

    public function submitfeedback(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'message' => 'required|string|max:1000',
        ]);

        // You can store it in DB or send an email
        UserFeedback::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Thank you for your feedback!');
    }

    public function userfeedback()
    {
        $feedbacks = UserFeedback::where('user_id', auth()->id())->latest()->get();
        return view('user.feedback.feedback_manage', compact('feedbacks'));
    }

}
