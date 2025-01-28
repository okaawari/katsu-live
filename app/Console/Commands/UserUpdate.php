<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;

class UserUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:user_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user role for expired users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Define the role ID you want to sync. You could also store this in a config or constant.
        $EXPIRED_ROLE_ID = 5;

        // If you want the current date/time:
        $today = Carbon::now();

        // Counter to keep track of how many users got updated
        $updatedCount = 0;

        /*
         * Use chunk (or chunkById) to process records in batches,
         * which is more efficient and avoids potential memory issues
         * when dealing with large datasets.
         */
        User::where('expire_date', '<', $today)
            ->orderBy('expire_date', 'desc')
            ->chunk(100, function ($users) use ($EXPIRED_ROLE_ID, &$updatedCount) {
                foreach ($users as $user) {
                    // Update user fields
                    $user->update([
                        'expire_date' => null,
                        'sub_date'    => null,
                    ]);

                    // Sync the new role
                    $user->roles()->sync([$EXPIRED_ROLE_ID]);

                    $updatedCount++;
                }
            });

        // Log or output how many records were updated
        $this->info("{$updatedCount} users updated.");
        
        return Command::SUCCESS;
    }
}
