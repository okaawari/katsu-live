<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Anime;
use App\Models\User;
use App\Models\Token;
use App\Models\PaymentHistory;
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
    protected $description = 'Syncing roles with user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $token = Token::where('id', '=', 1)->first();
        $access_token = $token->access_token;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.khanbank.com:9003/v1/omni/user/custom/recentTransactions?account=5167430233');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Accept-Language: en-US,en;q=0.5';
        $headers[] = 'Authorization: Bearer ' . $access_token;
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Origin: https://e.khanbank.com';
        $headers[] = 'Referer: https://e.khanbank.com/';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: same-site';
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36';
        $headers[] = 'App-Version: 1.3.21-rc.366';
        $headers[] = 'Secure: yes';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $result = json_decode($result);
        
        foreach($result as $res){
            
        }

        foreach($result as $res){
            if($res->amount == 3000){
                $ref = $res->refId;
                $refId = PaymentHistory::where('refId', '=', $ref)->first();

                if($refId == null){
                    $desc = $res->description;

                    $userId = substr($desc, -6);
                    $user = User::find($userId);

                    $userId2 = substr($desc, 0, 6);
                    $user2 = User::find($userId2);

                    if($user != null){
                        $user->sub_date = Carbon::now()->toDateTimeString();
                        $user->expire_date = Carbon::now()->addDays('30')->toDateTimeString();
                        $user->save();
                        $user->syncRoles(explode(' ', 4));

                        $transaction = New PaymentHistory;
                        $transaction->amount = $res->amount;
                        $transaction->user_id = $userId;
                        $transaction->refId = $res->refId;
                        $transaction->save();
                    } elseif($user2 != null) {
                        $user2->sub_date = Carbon::now()->toDateTimeString();
                        $user2->expire_date = Carbon::now()->addDays('30')->toDateTimeString();
                        $user2->save();
                        $user2->syncRoles(explode(' ', 4));

                        $transaction = New PaymentHistory;
                        $transaction->amount = $res->amount;
                        $transaction->user_id = $userId2;
                        $transaction->refId = $res->refId;
                        $transaction->save();
                    }
                }
            } elseif($res->amount == 6000){
                $ref = $res->refId;
                $refId = PaymentHistory::where('refId', '=', $ref)->first();

                if($refId == null){
                    $desc = $res->description;
                    $userId = substr($desc, -6);
                    $user = User::find($userId);

                    $userId2 = substr($desc, 0, 6);
                    $user2 = User::findorFail($userId2);

                    if($user != null){
                        $user->sub_date = Carbon::now()->toDateTimeString();
                        $user->expire_date = Carbon::now()->addDays('60')->toDateTimeString();
                        $user->save();
                        $user->syncRoles(explode(' ', 4));

                        $transaction = New PaymentHistory;
                        $transaction->amount = $res->amount;
                        $transaction->user_id = $userId;
                        $transaction->refId = $res->refId;
                        $transaction->save();
                    } elseif($user2 != null) {
                        $user2->sub_date = Carbon::now()->toDateTimeString();
                        $user2->expire_date = Carbon::now()->addDays('60')->toDateTimeString();
                        $user2->save();
                        $user2->syncRoles(explode(' ', 4));

                        $transaction = New PaymentHistory;
                        $transaction->amount = $res->amount;
                        $transaction->user_id = $userId2;
                        $transaction->refId = $res->refId;
                        $transaction->save();
                    }
                }
            }
        }

        return Command::SUCCESS;
    }
}
