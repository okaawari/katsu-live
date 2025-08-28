<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\PaymentHistory;
use Carbon\Carbon;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $baseUrl = config('app.url');
        $response = Http::get($baseUrl . '/transaction/test');
        $transactions = $response->json();

        if (!$transactions) {
            $this->error('Failed to fetch transactions or invalid response format');
            $this->error('Response: ' . $response->body());
            return Command::FAILURE;
        }

        $amountToDaysMap = [
            3000 => 30,
            5000 => 50,
            6000 => 60,
            9000 => 90,
        ];

        // 6. Process each transaction
        foreach ($transactions as $transaction) {
            // Ensure transaction has a valid amount
            if (!isset($transaction['amount']) || !isset($amountToDaysMap[$transaction['amount']])) {
                // Skip if amount not in our list (3000, 5000, 6000, 9000)
                continue;
            }

            // Skip if refId is missing or already exists
            $refId = $transaction['refId'] ?? null;
            if (!$refId || PaymentHistory::where('refId', $refId)->exists()) {
                continue;
            }

            // Parse user from description
            $desc = $transaction['description'] ?? '';
            if (!$desc) {
                continue;
            }

            // 7. Find the user by last or first 6-digit sequence in the description
            $user = $this->parseUserFromDescription($desc);
            if (!$user) {
                $this->warn("No user found for description: {$desc}");
                continue;
            }

            // 8. Upgrade user
            $days = $amountToDaysMap[$transaction['amount']];
            $this->upgradeUser($user, $days, $transaction['amount'], $refId);
            $this->info("Successfully upgraded user {$user->id} for {$days} days");
        }

        return Command::SUCCESS;
    }

    private function parseUserFromDescription(string $description): ?User
    {
        // Find all 6-digit sequences in the description
        if (preg_match_all('/(\d{6})/', $description, $matches)) {
            // $matches[1] is an array of all 6-digit numbers
            $allDigits = $matches[1];

            // 1) Try the last 6-digit sequence
            $lastSix = end($allDigits);
            $user = User::find($lastSix);
            if ($user) {
                return $user;
            }

            // 2) If not found, try the first 6-digit sequence
            $firstSix = reset($allDigits);
            $user = User::find($firstSix);
            if ($user) {
                return $user;
            }
        }

        return null; // No suitable user found
    }

    /**
     * Update user's subscription info and store a payment record.
     */
    private function upgradeUser(User $user, int $days, int $amount, string $refId): void
    {
        $now = Carbon::now();

        // Create Member role if it doesn't exist
        $role = \App\Models\Role::firstOrCreate(['name' => 'Member']);

        $expireDate = $user->expire_date ? Carbon::parse($user->expire_date) : null;

        if ($user->hasRole($role->name) && $expireDate && $expireDate->gt($now)) {
            // Extend from current expire_date
            $user->expire_date = $expireDate->addDays($days);
        } else {
            // Start new subscription
            $user->sub_date = $now;
            $user->expire_date = $now->copy()->addDays($days);
        }

        $user->save();

        // Assign role (only if not already assigned)
        if (!$user->hasRole($role->name)) {
            $user->addRole($role);
        }

        // Log payment
        PaymentHistory::create([
            'amount'  => $amount,
            'user_id' => $user->id,
            'refId'   => $refId,
        ]);
    }
}
