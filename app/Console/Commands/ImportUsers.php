<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportUsers extends Command
{
    protected $signature = 'app:import-users';
    protected $description = 'Import users from old_users table into users table (skip duplicate emails)';

    public function handle()
    {
        $oldUsers = DB::table('old_users')
            ->orderBy('created_at', 'asc') // ensure first created entry is preferred
            ->get();

        $countInserted = 0;
        $countSkipped = 0;

        foreach ($oldUsers as $old) {
            // check by email instead of id
            $exists = DB::table('users')->where('email', $old->email)->exists();

            if (! $exists) {
                DB::table('users')->insert([
                    'id'                      => $old->id,
                    'name'                    => $old->name,
                    'email'                   => $old->email,
                    'password'                => $old->password,
                    'bio'                     => $old->about,
                    'avatar'                  => $old->avatar,
                    'cover_image'             => $old->cover,
                    'subscription_expires_at' => $old->expire_date,
                    'subscription_date'       => $old->sub_date,
                    'created_at'              => $old->created_at,
                    'updated_at'              => $old->updated_at,
                ]);

                $countInserted++;
            } else {
                $countSkipped++;
            }
        }

        $this->info("✅ Import finished. Inserted: {$countInserted}, Skipped: {$countSkipped}");
    }
}
