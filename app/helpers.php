<?php

use App\Models\PackageHistory;
use App\Models\PricingPlan;
use App\Models\RequestModuleFeedback;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


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

        $freePricingPlan = PricingPlan::where('slug', 'free_monthly')->first();

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

        if ($user) {
            // Check if the user has enough tokens or credits
            if ($user->tokens_left < $tokens) {
                return "no-tokens";
            }

            if ($user->credits_left < $credits) {
                return "no-credits";
            }

            // Deduct the tokens and credits
            $user->tokens_left = max(0, $user->tokens_left - $tokens);
            $user->tokens_used = max(0, $user->tokens_used + $tokens);
            $user->credits_left = max(0, $user->credits_left - $credits);

            // Save the changes to the database
            $user->save();

            return "deducted-successfully";
        }

        return "User not found";
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
            $apiKey = config('app.openai_api_key');
            $client = OpenAI::client($apiKey);
            $openaiModel = $user->selected_model;
    
            try {
                // Send the request to OpenAI
                $response = $client->chat()->create([
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

}






