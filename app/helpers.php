<?php

use App\Models\ButtonStyle;
use App\Models\PackageHistory;
use App\Models\PricingPlan;
use App\Models\RequestModuleFeedback;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;


if (!function_exists('calculateCredits')) {
    function calculateCredits($resolution, $quality)
    {
        switch ($resolution) {
            case '512x512':
                return 1;
            case '1024x1024':
                if ($quality === 'hd') {
                    return 2;
                } else {
                    return 1;
                }
            case '1792x1024':
            case '1024x1792':
                if ($quality === 'hd') {
                    return 3;
                } else {
                    return 2;
                }
            default:
                return 1;
        }
    }
}

// GET MODEL FROM PACKAGE
if (!function_exists('getUserLastPackageAndModels')) {
    function getUserLastPackageAndModels()
    {
        $user = Auth::user();

        if (!$user) {
            return ['lastPackage' => null, 'aiModels' => [], 'selectedModel' => null];
        }

        // Get the user's last package
        $lastPackage = PackageHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $freePricingPlan = PricingPlan::where('package_type', 'monthly')
        ->where('slug', 'like', '%free%')
        ->first();
        

        // Initialize the AI models array
        $aiModels = [];

        if ($lastPackage) {
            // Get the PricingPlan associated with the last package
            $pricingPlan = PricingPlan::find($lastPackage->package_id);

            if ($pricingPlan) {
                // Extract AI models
                $models = explode(',', $pricingPlan->open_id_model);
                foreach ($models as $index => $model) {
                    $aiModels[] =  $model;
                }
            }
        } else {   
            if ($freePricingPlan) {
                // Extract AI models
                $models = explode(',', $freePricingPlan->open_id_model);
                foreach ($models as $index => $model) {
                    $aiModels[] =  $model;
                }
            }
        }

        // Get the currently selected model
        $selectedModel = $user->selected_model;

        return compact('lastPackage', 'aiModels', 'selectedModel', 'freePricingPlan');
    }
}

// Activity LOG
if (!function_exists('log_activity')) {
    function log_activity($message)
    {
        $user_id = Auth::id(); // Get the authenticated user's ID

        // Get the count of activity logs for the user
        $activityLogCount = \App\Models\ActivityLog::where('user_id', $user_id)->count();

        // If the user already has 10 activity logs, delete the oldest one
        if ($activityLogCount >= 10) {
            \App\Models\ActivityLog::where('user_id', $user_id)
                ->orderBy('created_at', 'asc') // Oldest first
                ->first() // Get the oldest record
                ->delete(); // Delete it
        }

        // Create a new activity log entry
        \App\Models\ActivityLog::create([
            'user_id' => $user_id,
            'message' => $message,
        ]);
    }
}

// DEDUCT TOKENS
if (!function_exists('deductUserTokensAndCredits')) {
    function deductUserTokensAndCredits(int $tokens = 0, int $credits = 0)
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);

        if (!$user) {
            return "User not authenticated";
        }

        $year = now()->year;
        $month = now()->month;

        // Find or create monthly usage record
        $monthlyUsage = \App\Models\UserMonthlyUsage::firstOrCreate(
            [
                'user_id' => $user_id,
                'year' => $year,
                'month' => $month,
            ],
            [
                'tokens_used' => 0,
                'credits_used' => 0,
            ]
        );

        // Deduct tokens
        if ($user->tokens_left >= $tokens) {
            $user->tokens_left = max(0, $user->tokens_left - $tokens);
            $user->tokens_used += $tokens;
            $monthlyUsage->tokens_used += $tokens;
        } else {
            $user->free_tokens_used += $tokens;
        }

        // Deduct credits
        if ($user->credits_left < $credits) {
            return "no-credits";
        }

        $user->credits_left = max(0, $user->credits_left - $credits);
        $user->credits_used += $credits;
        $monthlyUsage->credits_used += $credits;

        // Increment images_generated only if credits are used
        if ($credits > 0) {
            $user->images_generated += 1;
        }

        // Save changes
        $user->save();
        $monthlyUsage->save();

        return "deducted-successfully";
    }
}


if (!function_exists('get_days_until_next_reset')) {
    function get_days_until_next_reset($user_id = null)
    {
        if (!$user_id) {
            $user_id = Auth::id();
        }

        $user = User::findOrFail($user_id);
        $packageHistory = $user->packageHistory()->with('package')->get();

        $daysUntilNextReset = null;

        if ($packageHistory->isEmpty()) {
            // Free plan case
            $freePricingPlan = PricingPlan::where('title', 'Free')->first();

            // Calculate the next reset date for free plan
            $now = Carbon::now();
            $registrationDate = $user->created_at;
            $nextResetDate = $registrationDate->copy()->addMonths($registrationDate->diffInMonths($now) + 1);
            $daysUntilNextReset = $now->diffInDays($nextResetDate);
        } else {
            // Paid plan case, handle only the last paid package
            $firstPaidPackage = $packageHistory->last();

            if ($firstPaidPackage) {
                // Calculate the next reset date for the first paid package
                $now = Carbon::now();
                $startDate = $firstPaidPackage->created_at;
                $nextResetDate = $startDate->copy()->addMonths($startDate->diffInMonths($now) + 1);
                $daysUntilNextReset = $now->diffInDays($nextResetDate);
            }
        }

        return $daysUntilNextReset;
    }
}

// Feedback Module Request
if (!function_exists('saveModuleFeedback')) {
    function saveModuleFeedback(string $module, string $text)
    {
        $user_id = Auth::user()->id;

        // Create a new RequestModuleFeedback instance
        $feedback = new RequestModuleFeedback();
        $feedback->user_id = $user_id;
        $feedback->module = $module;
        $feedback->text = $text;
        $feedback->status = "pending";

        // Save the feedback to the database
        if ($feedback->save()) {
            return "feedback-saved-successfully";
        }

        return "failed-to-save-feedback";
    }


    if (!function_exists('rephrasePrompt')) {
        function rephrasePrompt($prompt)
        {
            // Initialize the OpenAI Client
            $user = auth()->user();
         
            $openaiModel = $user->selected_model;
            
            try {
                // Send the request to OpenAI
                $response = OpenAI::chat()->create([
                    'model' => $openaiModel,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a helpful assistant. Please rephrase the following image generation prompt to enhance its creativity and captivation while maintaining the core theme and details provided by the user. Ensure the rewritten prompt with a high level of creativity and a professional tone.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ],
                    ],
                ]);
    
                $rephrasedPrompt = $response['choices'][0]['message']['content'];

            // Log the original and rephrased prompts
            Log::info('Original Prompt:', ['prompt' => $prompt]);
            Log::info('Rephrased Prompt:', ['rephrased' => $rephrasedPrompt]);

            // Return the rephrased prompt
            return $rephrasedPrompt;

            } catch (Exception $e) {
                // Handle any errors
                Log::error("Error with OpenAI API: " . $e->getMessage());
                return "Error: Unable to rephrase the prompt at this time.";
            }
        }
    }


    if (!function_exists('checkOptimizePrompt')) {
        function checkOptimizePrompt($prompt, $request)
        {
            $optimizePrompt = $request->input('hiddenPromptOptimize') ?? '0';
            
            if ($optimizePrompt == '1') {
                // Call the rephrasePrompt function if optimization is enabled
                return rephrasePrompt($prompt);
            }
    
            // Return the original prompt if optimization is not enabled
            return $prompt;
        }
    }


// Extract Prompt From Image
if (!function_exists('callOpenAIImageAPI')) {
    function callOpenAIImageAPI($base64Image)
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => 'What’s in this image?'],
                            ['type' => 'image_url', 'image_url' => [
                                'url' => 'data:image/jpeg;base64,' . $base64Image,
                            ]],
                        ],
                    ],
                ],
                'max_tokens' => 300,
            ]);

            return $response;
        } catch (Exception $e) {
            // Handle exceptions, log errors, or return a meaningful message
            return ['error' => $e->getMessage()];
        }
    }
}

// User Activity Log (ADMIN)
if (!function_exists('logActivity')) {
    function logActivity($action, $details = null)
    {
        if (auth()->check()) {
            $userId = auth()->id();
            $role = auth()->user()->role;  // Assuming 'role' is a field in your User model
            
            // Append the role to the details
            $roleText = ($role == 'admin') ? 'Admin' : 'User';  // Adjust role check based on your role values
            $detailsWithRole = $roleText . ' - ' . $details;

            // Insert the new log with dynamic role in details
            UserActivityLog::create([
                'user_id' => $userId,
                'action' => $action,
                'details' => $detailsWithRole,  // Save the role info in details
            ]);

            // Keep only the latest 20 logs for the user
            $excessLogs = UserActivityLog::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->skip(20)
                ->take(PHP_INT_MAX)
                ->pluck('id');

            if ($excessLogs->isNotEmpty()) {
                UserActivityLog::whereIn('id', $excessLogs)->delete();
            }
        }
    }
}

if (!function_exists('getButtonClass')) {
    function getButtonClass($type)
    {
        return ButtonStyle::where('button_type', $type)->where('is_selected', true)->value('class_name') ?? 'btn btn-default';
    }
}

// Chwck if the user has tokens
if (!function_exists('userHasTokensLeft')) {
    function userHasTokensLeft()
    {
        $user = Auth::user();

        if (!$user) {
            return false; // No user logged in
        }
        
        return $user->tokens_left > 0;
    }
}


// FOR API HELPERS
if (!function_exists('deductUserTokensAndCreditsAPI')) {
    function deductUserTokensAndCreditsAPI($user, int $tokens = 0, int $credits = 0)
    {

        Log::info('Inside Deducat API 356');
        if (!$user) {
            return "User not authenticated";
        }

        // If the user has tokens, deduct normally
        if ($user->tokens_left >= $tokens) {
            $user->tokens_left = max(0, $user->tokens_left - $tokens);
            $user->tokens_used = max(0, $user->tokens_used + $tokens);
        } else {
            // If the user has no tokens, track free generations instead
            $user->free_tokens_used += $tokens;
        }

        // Deduct credits if required
        if ($user->credits_left < $credits) {
            return "no-credits";
        }

        $user->credits_left = max(0, $user->credits_left - $credits);
        $user->credits_used = max(0, $user->credits_used + $credits);

        // Increment images_generated only if credits are used
        if ($credits > 0) {
            $user->images_generated += 1;
        }

        // Save changes
        $user->save();

        return "deducted-successfully";
    }
}

    

}






