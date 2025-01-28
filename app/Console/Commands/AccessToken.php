<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Token;

class AccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:access_token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get access token from API and store it in the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $username  = config('services.khanbank.username');
        $password  = config('services.khanbank.password');
        $basicAuth = config('services.khanbank.basic_auth');

        // IMPORTANT: Replicate the raw JSON string exactly as your cURL snippet
        // cURL was sending:
        //   curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"grant_type\":\"password\",\"username\":\"...\",\"password\":\"...\",\"channelId\":\"I\",\"languageId\":\"003\"}");
        // but with `Content-Type: application/x-www-form-urlencoded`.

        $payload = json_encode([
            'grant_type'  => 'password',
            'username'    => $username,
            'password'    => $password,
            'channelId'   => 'I',
            'languageId'  => '003'
        ]);

        // Build the request with the same headers from your working cURL
        $response = Http::withHeaders([
            'Accept'            => 'application/json, text/plain, */*',
            'Accept-Language'   => 'en-US,en;q=0.5',
            'Authorization'     => 'Basic ' . $basicAuth,
            'Connection'        => 'keep-alive',
            'Content-Type'      => 'application/x-www-form-urlencoded',
            'Origin'            => 'https://e.khanbank.com',
            'Referer'           => 'https://e.khanbank.com/',
            'Sec-Fetch-Dest'    => 'empty',
            'Sec-Fetch-Mode'    => 'cors',
            'Sec-Fetch-Site'    => 'same-site',
            'Sec-Gpc'           => '1',
            'User-Agent'        => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
            'App-Version'       => '1.3.21-rc.366',
            'Device-Id'         => 'B4DADC4F-50CE-4599-A0D6-A64901398530',
            'Secure'            => 'yes',
        ])
        ->withBody($payload, 'application/x-www-form-urlencoded')
        ->post('https://api.khanbank.com:9003/v1/cfrm/auth/token?grant_type=password');

        // Check response
        if ($response->successful()) {
            $data = $response->json();

            if (! isset($data['access_token'], $data['refresh_token'])) {
                $this->error('The response does not contain the expected token fields.');
                return Command::FAILURE;
            }

            Token::updateOrCreate(['id' => 1], [
                'access_token'  => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
            ]);

            $this->info('Access and refresh tokens have been successfully updated!');
            return Command::SUCCESS;
        }

        // Show the error details if the request fails
        $this->error('Failed to retrieve token. Status: ' . $response->status());
        $this->error('Response Body: ' . $response->body());

        return Command::FAILURE;
    }
}
