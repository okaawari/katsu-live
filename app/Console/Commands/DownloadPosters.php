<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DownloadPosters extends Command
{
    protected $signature   = 'download:posters';
    protected $description = 'Download posters from animes table';

    public function handle()
    {
        // Optionally, extend execution time
        set_time_limit(0);

        $animes = DB::table('animes')->select('id', 'poster')->get();
        foreach ($animes as $anime) {
            if (!$anime->poster) {
                continue;
            }

            $extension = pathinfo($anime->poster, PATHINFO_EXTENSION);
            $filename  = $anime->id . '.' . $extension;

            try {
                $contents = file_get_contents($anime->poster);
                Storage::disk('public')->put('poster/' . $filename, $contents);

                $this->info("Downloaded ID {$anime->id} as {$filename}");
            } catch (\Exception $e) {
                $this->error("Error ID {$anime->id}: {$e->getMessage()}");
            }
        }

        return 0;
    }
}
