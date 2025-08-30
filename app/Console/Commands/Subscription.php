<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Anime;
use App\Models\User;
use App\Models\Token;
use App\Models\PaymentHistory;
use App\Models\TransactionHistory;
use Carbon\Carbon;

class Subscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncing roles with user subscriptions based on transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Fetch Token
        $token = Token::find(1);
        if (!$token || !$token->access_token) {
            $this->error('No valid access token found.');
            return Command::FAILURE;
        }

        // 2. Prepare cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.khanbank.com:9003/v1/omni/user/custom/recentTransactions?account=5167430233');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = [
            'Accept: application/json, text/plain, */*',
            'Accept-Language: en-US,en;q=0.5',
            'Authorization: Bearer ' . $token->access_token,
            'Connection: keep-alive',
            'Origin: https://e.khanbank.com',
            'Referer: https://e.khanbank.com/',
            'Sec-Fetch-Dest: empty',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Site: same-site',
            'Sec-Gpc: 1',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
            'App-Version: 1.3.21-rc.366',
            'Secure: yes',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // 3. Execute cURL request
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $this->error('cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return Command::FAILURE;
        }
        curl_close($ch);

        // 4. Decode JSON
        $transactions = json_decode($result);
        if (!is_array($transactions)) {
            $this->error('Invalid response or no transactions found.');
            return Command::FAILURE;
        }

        // 5. Define amount->days mapping
        $amountToDaysMap = [
            3000 => 30,
            5000 => 50,
            6000 => 60,
            9000 => 90,
        ];

        // 6. Process each transaction
        foreach ($transactions as $transaction) {
            // Ensure transaction has a valid amount
            if (!isset($transaction->amount) || !isset($amountToDaysMap[$transaction->amount])) {
                // Skip if amount not in our list (3000, 5000, 6000, 9000)
                continue;
            }

            // Skip if refId is missing or already exists
            $refId = $transaction->refId ?? null;
            if (!$refId || PaymentHistory::where('refId', $refId)->exists()) {
                continue;
            }

            // Parse user from description
            $desc = $transaction->description ?? '';
            if (!$desc) {
                continue;
            }

            // 7. Find the user by last or first 6-digit sequence in the description
            $user = $this->parseUserFromDescription($desc);
            if (!$user) {
                // If needed, you can log or do something else if user is not found
                continue;
            }

            // 8. Upgrade user
            $days = $amountToDaysMap[$transaction->amount];
            $this->upgradeUser($user, $days, $transaction->amount, $refId, $transaction->tranDate, $transaction->time, $transaction->description, $transaction->code, $transaction->currency, $transaction->balance);
        }

        return Command::SUCCESS;
    }

    /**
     * Parse a transaction description to find a valid 6-digit user ID.
     * Tries the last 6-digit sequence first, then the first 6-digit sequence.
     */
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
    private function upgradeUser(User $user, int $days, int $amount, string $refId, string $tranDate, string $time, string $description, string $code, string $currency, string $balance): void
    {
        // Get current time
        $now = Carbon::now();

        // If user already has the role (e.g., role ID 4), extend expire_date
        $hasRole = $user->hasRole(4); // Adjust role ID or name if needed

        if ($hasRole && $user->expire_date && $user->expire_date->gt($now)) {
            $user->expire_date = $user->expire_date->addDays($days);
        } else {
            // If user doesn't have role or it's expired, reset it
            $user->sub_date = $now;
            $user->expire_date = $now->copy()->addDays($days);
        }

        // Save user
        $user->save();

        // Assign role if not already assigned
        $user->syncRoles([4]);

        // Create payment history
        PaymentHistory::create([
            'amount' => $amount,
            'user_id' => $user->id,
            'refId'   => $refId,
        ]);
    }
}
