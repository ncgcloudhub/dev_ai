<?php

namespace App\Console\Commands;

use App\Models\PricingPlan;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ResetMonthlyUserTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:monthlyusertokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset user tokens every month from their registration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $users = User::all();

        foreach ($users as $user) {
            $monthsSinceRegistration = $user->created_at->diffInMonths($now);

            if ($monthsSinceRegistration > 0 && $now->day == $user->created_at->day) {
                // Get last package and free package details
                $userPackageData = getUserLastPackageAndModels();

                $lastPackage = $userPackageData['lastPackage'];
                $freePricingPlan = $userPackageData['freePricingPlan'];

                // Initialize tokens
                $tokens = 0;
                $credits = 0;

                if ($lastPackage) {
                    // If the user has purchased a package, set tokens based on the package
                    $pricingPlan = PricingPlan::find($lastPackage->package_id);
                    if ($pricingPlan) {
                        $tokens = $pricingPlan->tokens; // Assuming tokens is a field in the PricingPlan model
                        $credits = $pricingPlan->credits; // Assuming credits is a field in the PricingPlan model
                    }

                    // Add any remaining free package tokens
                    if ($freePricingPlan && $user->tokens_left > 0) {
                        $tokens += $user->tokens_left;
                    }
                } else {
                    // If no package purchased, use the free package tokens
                    $tokens = $freePricingPlan->tokens ?? 5000; // Default to 5000 if no free package tokens
                    $credits = $freePricingPlan->credits ?? 100; // Default to 100 if no free package credits
                }

                // Update user tokens and credits
                $user->tokens_left = $tokens;
                $user->credits_left = $credits;
                $user->save();
            }
        }

        $this->info('Monthly user tokens have been reset successfully.');
    }
}
