<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportYear extends Command
{
    protected $signature = 'app:import-year';
    protected $description = 'Import aired_at -> release_date and old created_at into episodes';

    public function handle()
    {
        DB::table('episodes as e')
            ->join('old_animes as oa', function ($join) {
                $join->on(DB::raw('CONVERT(e.title_english USING utf8mb4) COLLATE utf8mb4_unicode_ci'), '=', DB::raw('oa.name_second COLLATE utf8mb4_unicode_ci'))
                    ->on('e.episode_number', '=', 'oa.current_episode');
            })
            ->where(function ($q) {
                $q->whereNull('e.release_date')
                ->orWhere('e.release_date', '0000-00-00')
                ->orWhereNull('e.created_at');
            })
            ->update([
                'e.release_date' => DB::raw('oa.aired_at'),
                'e.created_at'   => DB::raw('oa.created_at'),
            ]);


        $this->info("✅ Release dates and created_at imported successfully.");
    }
}
