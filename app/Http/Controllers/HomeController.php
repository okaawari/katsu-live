<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Anime;
use App\Models\Episode;
use App\Models\Tag;
use App\Models\VideoProgress;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the home page with episode listings.
     */
    public function index(Request $request)
    {
        try {
            // Latest episodes
            $episodes = Episode::where('status', 'published')
                ->where('visibility', 'public')
                ->with(['anime', 'anime.category', 'tags'])
                ->orderBy('published_at', 'desc')
                ->take(10)
                ->get();

            // Oldest episodes
            $episodes1 = Episode::where('status', 'published')
                ->where('visibility', 'public')
                ->with(['anime', 'anime.category', 'tags'])
                ->orderBy('published_at', 'asc')
                ->take(12)
                ->get();

            // Most viewed episodes
            $episodes2 = Episode::where('status', 'published')
                ->where('visibility', 'public')
                ->with(['anime', 'anime.category', 'tags'])
                ->orderBy('view_count', 'desc')
                ->take(12)
                ->get();

            // Random episodes (cached for 60 seconds)
            $random = Cache::remember('random_episodes', 60, function() {
                return Episode::where('status', 'published')
                    ->where('visibility', 'public')
                    ->with(['anime', 'anime.category', 'tags'])
                    ->inRandomOrder()
                    ->take(10)
                    ->get();
            });

            $user = $request->user();
            $watching = collect();

            if ($user) {
                $watching = VideoProgress::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->with(['episode', 'episode.anime'])
                    ->take(4)
                    ->get();
            }

            // Debug: Log the data being passed to the view
            \Log::info('HomeController data:', [
                'episodes_count' => $episodes->count(),
                'episodes1_count' => $episodes1->count(),
                'episodes2_count' => $episodes2->count(),
                'random_count' => $random->count(),
                'watching_count' => $watching->count(),
                'user_id' => $user ? $user->id : 'not logged in'
            ]);

            return view('home', compact('episodes', 'episodes1', 'episodes2', 'random', 'watching'));

        } catch (\Exception $e) {
            \Log::error('Error in HomeController@index: ' . $e->getMessage());
            
            return view('home', [
                'episodes' => collect(),
                'episodes1' => collect(),
                'episodes2' => collect(),
                'random' => collect(),
                'watching' => collect(),
            ])->with('error', 'Unable to load content. Please try again later.');
        }
    }
}
