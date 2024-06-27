<?php

namespace App\Console\Commands;

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
        // Get current date
        $now = Carbon::now();

        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Calculate the difference in months between now and the user's registration date
            $monthsSinceRegistration = $user->created_at->diffInMonths($now);

            // If it's been a whole number of months since registration (e.g., 1 month, 2 months, etc.)
            if ($monthsSinceRegistration > 0 && $now->day == $user->created_at->day) {
                $user->tokens_left = 5000;
                $user->credits_left = 100;
                $user->save();
            }
        }

        $this->info('Monthly user tokens have been reset successfully.');
    }
}
