<?php

namespace App\Console\Commands;

use App\Models\PackageHistory;
use App\Models\PricingPlan;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RenewFreePackageForNonSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:renew-free-for-non-subscribers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew free monthly package for users who have not purchased any subscription.';


    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    // Execute the console command
    public function handle()
    {
        $users = User::all(); // Get all users

        foreach ($users as $user) {
            // Check if the user has no active paid subscription
            if (!$user->hasActivePaidSubscription()) {
                $lastRenewed = $user->last_free_package_renewed_at;

                // Renew the free package if the user hasn't been renewed in the past 30 days
                if (!$lastRenewed || $lastRenewed->diffInDays(now()) >= 30) {
                    $freePlan = PricingPlan::where('slug', 'free_monthly')->first();
                    if ($freePlan) {
                        // Add tokens and images to the user
                        $user->increment('credits_left', $freePlan->images);
                        $user->increment('tokens_left', $freePlan->tokens);

                        // Log the renewal in package history
                        PackageHistory::create([
                            'user_id' => $user->id,
                            'package_id' => $freePlan->id,
                            'invoice' => 'free-renewal-' . now()->format('YmdHis'),
                            'package_amount' => 0,
                        ]);

                        // Update the user's last free package renewal date
                        $user->update([
                            'last_free_package_renewed_at' => now()
                        ]);

                        Log::info('Free package renewed for user ID: ' . $user->id);
                    }
                }
            }
        }

        $this->info('Free package renewal task completed for all non-subscribed users.');
    }
}
