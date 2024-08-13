<?php

use App\Models\PackageHistory;
use App\Models\PricingPlan;
use Illuminate\Support\Facades\Auth;

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
        }

        // Get the currently selected model
        $selectedModel = $user->selected_model;

        return compact('lastPackage', 'aiModels', 'selectedModel');
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




