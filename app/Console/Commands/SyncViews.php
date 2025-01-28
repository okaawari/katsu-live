<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class SyncViews extends Command
{
    protected $signature = 'sync:views';
    protected $description = 'Sync Redis view counts to the database';

    public function handle()
    {
        // Typically, you want to sync **yesterday**'s data 
        // (to be sure you have final counts for that day).
        // But if you prefer to do same-day, you can adjust the date below.
        // $date = Carbon::yesterday()->format('Y-m-d');

        $date = Carbon::now()->format('Y-m-d');

        // We'll SCAN for keys matching "post:*:views:YYYY-MM-DD"
        $pattern = "anime:*:views:$date";

        // Initialize SCAN
        $cursor = 0;

        $this->info("Scanning for pattern: $pattern");

        do {
            list($cursor, $keys) = Redis::scan($cursor, [
                'MATCH' => $pattern,
                'COUNT' => 100,
            ]);

            // Show keys found
            $this->info("Found keys: " . implode(', ', $keys));

            foreach ($keys as $key) {
                $segments = explode(':', $key);
                $animeId = $segments[1];
                $viewsCount = Redis::get($key);

                $this->info("Key: $key => animeId=$animeId, viewsCount=$viewsCount");

                if ($viewsCount > 0) {
                    // BEFORE doing anything, let's see if row already exists
                    $rowExists = DB::table('anime_views')
                        ->where('anime_id', $animeId)
                        ->where('date', $date)
                        ->exists();

                    $this->info("Row exists in anime_views? " . ($rowExists ? 'YES' : 'NO'));

                    // Try the 2-step upsert approach:
                    if ($rowExists) {
                        DB::table('anime_views')
                            ->where('anime_id', $animeId)
                            ->where('date', $date)
                            ->update([
                                'views_count' => DB::raw("views_count + $viewsCount"),
                                'updated_at' => now(),
                            ]);
                    } else {
                        DB::table('anime_views')->insert([
                            'anime_id' => $animeId,
                            'date' => $date,
                            'views_count' => $viewsCount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // Check the final value from DB
                    $finalCount = DB::table('anime_views')
                        ->where('anime_id', $animeId)
                        ->where('date', $date)
                        ->value('views_count');

                    $this->info("Final views_count in DB is $finalCount");

                    // All-time increment in 'animes'
                    DB::table('animes')
                        ->where('id', $animeId)
                        ->increment('views', $viewsCount);

                    // Delete the Redis key
                    Redis::del($key);
                }
            }
        } while ($cursor != 0);

        $this->info("Successfully synced Redis views for $date into the database.");

    }
}
