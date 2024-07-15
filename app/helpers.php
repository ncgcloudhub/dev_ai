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

// GET MODEL FRO  PACKAGE
if (!function_exists('getUserLastPackageAndModels')) {
    function getUserLastPackageAndModels()
    {
        $user = Auth::user();

        if (!$user) {
            return ['lastPackage' => null, 'aiModels' => []];
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

        return compact('lastPackage', 'aiModels');
    }
}

