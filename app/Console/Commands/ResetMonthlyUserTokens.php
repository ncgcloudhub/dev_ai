<?php

namespace App\Console\Commands;

use App\Models\PackageHistory;
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
            // Retrieve the user's last package
            $lastPackage = PackageHistory::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
    
            // Retrieve the free pricing plan
            $freePricingPlan = PricingPlan::where('title', 'Free')->first();
    
            // Determine the renewal date based on the last package or registration date
            $renewalDate = $lastPackage ? $lastPackage->created_at : $user->created_at;
    
            // Calculate the number of months since the last renewal date
            $monthsSinceRenewal = $renewalDate->diffInMonths($now);
    
            // If it's been a full month and it's the same day of the month as the renewal date
            if ($monthsSinceRenewal > 0 && $now->day == $renewalDate->day) {
                $tokens = 0;
                $credits = 0;
    
                if ($lastPackage) {
                    // Get the associated pricing plan
                    $pricingPlan = PricingPlan::find($lastPackage->package_id);
                    if ($pricingPlan) {
                        $tokens = $pricingPlan->tokens ?? 0;
                        $credits = $pricingPlan->images ?? 0;
                    }
    
                    // Add any leftover tokens from the free package
                    if ($freePricingPlan && $user->tokens_left > 0) {
                        $tokens = $user->tokens_left;
                        $credits = $user->credits_left;
                    }
                } else {

                    if ($freePricingPlan) {
                        $tokens = $freePricingPlan->tokens;
                        $credits = $freePricingPlan->images;
                    }
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
