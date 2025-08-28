<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DownloadValidPosters extends Command
{
    protected $signature = 'command:download-valid-posters';
    protected $description = 'Download available Imgur images, save locally, and update DB';

    public function handle()
    {
        $animes = DB::table('animes')->get();

        foreach ($animes as $anime) {
            $url = $anime->poster;

            if (!$url || !Str::contains($url, 'imgur')) {
                $this->warn("Skipping anime ID {$anime->id}: invalid or non-imgur URL");
                continue;
            }

            try {
                // HEAD check if image exists
                $head = Http::head($url);

                if ($head->status() !== 200) {
                    $this->warn("Image not available for ID {$anime->id}");
                    continue;
                }

                // Get image extension
                $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $filename = "{$anime->id}.{$ext}";
                $localPath = public_path("/image/{$filename}");

                // Download and save
                $image = Http::get($url);
                $imageData = $image->body();

                // Check if it's a placeholder (common Imgur ones are small)
                if (strlen($imageData) < 20000) {
                    $this->warn("Image likely missing (placeholder) for ID {$anime->id}, skipping.");
                    continue;
                }

                // Update DB
                $newPosterUrl = "https//fukkatsu.club/images/{$filename}";
                DB::table('animes')
                    ->where('id', $anime->id)
                    ->update(['poster' => $newPosterUrl]);

                $this->info("Downloaded & updated: ID {$anime->id}");
            } catch (\Exception $e) {
                $this->error("Failed ID {$anime->id}: " . $e->getMessage());
            }
        }
    }
}
