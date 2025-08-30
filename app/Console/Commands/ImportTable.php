<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Anime;
use App\Models\Episode;

class ImportTable extends Command
{
    protected $signature = 'import:old-animes';
    protected $description = 'Import old animes into new structure (Anime + Episodes + Tags)';

    public function handle()
    {
        // Fetch all old animes
        $oldAnimes = DB::table('old_animes')->get(); // <-- rename your old table name here

        // Group by name_second
        $grouped = $oldAnimes->groupBy('name_second');

        foreach ($grouped as $secondName => $rows) {
            $first = $rows->first();

            // Create Anime
            $anime = Anime::create([
                'category_id'     => $first->category_id,
                'author_id'       => $first->author_id,
                'title'           => $secondName ?: $first->name,
                'title_english'   => $first->name_second ?? $first->name,
                'title_japanese'  => $first->name_japanese,
                'duration'        => $first->duration ?? '24 minutes',
                'slug'            => Str::slug($secondName ?: $first->name) . '-' . uniqid(),
                'studio'          => $first->studio,
                'description'     => $first->synopsis,
                'status'          => ($first->status == 0) ? 'completed' : 'ongoing',
                'total_episodes'  => $rows->max('episode_list'),
                'cover_image'     => $first->poster,
                'view_count'      => $rows->sum('views'),
                'published_at'    => $first->aired_at ? now() : null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            $this->info("Created anime: {$anime->title}");

            // Grab old tags for this anime
            $oldTags = DB::table('anime_tag')
                ->where('anime_id', $first->id)
                ->pluck('tag_id'); // just tag ids

            // Create Episodes
            foreach ($rows as $row) {
                $episode = Episode::create([
                    'anime_id'          => $anime->id,
                    'uploaded_by'       => $row->author_id,
                    'episode_number'    => $row->current_episode,
                    'title'             => $row->name,
                    'title_english'     => $row->name_second,
                    'title_japanese'    => $row->name_japanese,
                    'duration'          => $row->duration ?? $anime->duration,
                    'synopsis'          => $row->synopsis,
                    'slug'              => Str::slug($row->name) . '-' . uniqid(),
                    'release_date'      => $row->aired_at,
                    'poster_image'      => $row->poster,
                    'video_480p'        => $row->stream_480,
                    'video_720p'        => $row->stream_720,
                    'video_1080p'       => $row->stream_1080,
                    'subtitle_english'  => $row->sub_eng,
                    'subtitle_mongolian'=> $row->sub_mn,
                    'view_count'        => $row->views ?? 0,
                    'visibility'        => 'public',
                    'published_at'      => $row->aired_at ? now() : null,
                    'created_at'        => $row->created_at,
                    'updated_at'        => $row->updated_at,
                ]);

                // Attach old tags to this episode
                foreach ($oldTags as $tagId) {
                    DB::table('episode_tag')->insert([
                        'episode_id' => $episode->id,
                        'tag_id'     => $tagId,
                    ]);
                }
            }

            $this->info("  -> Imported " . count($rows) . " episodes with " . count($oldTags) . " tags each.");
        }

        $this->info("✅ Import completed!");
    }
}
