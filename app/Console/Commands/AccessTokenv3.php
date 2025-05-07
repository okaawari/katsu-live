<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Token;

class AccessTokenv3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:access_v3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch access token from Khan Bank API v3 and store it';

    public function handle(): int
    {
        // Pull credentials from config
        $username = config('services.khanbank.username');
        $password = config('services.khanbank.password');
        $xDtpc    = config('services.khanbank.x_dtpc');

        // Prepare JSON payload
        $payload = [
            'grant_type' => 'password',
            'username'   => $username,
            'password'   => $password,
            'channelId'  => 'I',
            'languageId' => '003',
        ];

        // Replicate your cURL headers exactly
        $headers = [
            'Accept'            => 'application/json, text/plain, */*',
            'Accept-Language'   => 'mn-MN',
            'Connection'        => 'keep-alive',
            'Content-Type'      => 'application/json',
            'Origin'            => 'https://e.khanbank.com',
            'Referer'           => 'https://e.khanbank.com/auth/login',
            'Sec-Fetch-Dest'    => 'empty',
            'Sec-Fetch-Mode'    => 'cors',
            'Sec-Fetch-Site'    => 'same-origin',
            'Sec-Gpc'           => '1',
            'User-Agent'        => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
            'App-Version'       => '0.0.9',
            'Device-Id'         => '86CC2A4F-22C2-4C06-AA76-7ACC57A7779A',
            'Sec-Ch-Ua'         => '"Brave";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
            'Sec-Ch-Ua-Mobile'  => '?0',
            'Sec-Ch-Ua-Platform'=> '"Windows"',
            'Secure'            => 'yes',
            'X-Dtpc'            => $xDtpc,
        ];

        // Send the request
        $response = Http::withHeaders($headers)
                        ->post('https://e.khanbank.com/v3/cfrm/auth/token', $payload);

        // Handle response
        if ($response->successful()) {
            $data = $response->json();

            // Ensure tokens exist
            if (! isset($data['access_token'], $data['refresh_token'])) {
                $this->error('Response missing access_token or refresh_token.');
                return Command::FAILURE;
            }

            // Upsert into DB
            Token::updateOrCreate(
                ['id' => 1],
                [
                    'access_token'  => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                ]
            );

            $this->info('Tokens updated successfully.');
            return Command::SUCCESS;
        }

        // Error details
        $this->error('Failed to retrieve token. Status: ' . $response->status());
        $this->error('Response Body: ' . $response->body());

        return Command::FAILURE;
    }
}
