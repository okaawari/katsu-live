<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anime;
use App\Models\Episode;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;
use App\Models\VideoProgress;
use Illuminate\Support\Facades\Auth;

class WatchController extends Controller
{
    /**
     * Display the watch page for a specific episode.
     */
    public function index(Request $request, $slug) 
    {
        try {
            // Find the episode by slug
            $episode = Episode::where('slug', $slug)
                ->where('status', 'published')
                ->where('visibility', 'public')
                ->with(['anime', 'anime.category', 'anime.tags', 'tags'])
                ->firstOrFail();
            
            $anime = $episode->anime;

            // Increment view count in Redis
            $redisKey = "anime:{$anime->id}:views:" . Carbon::now()->format('Y-m-d');
            Redis::incr($redisKey);

            // Get random episode suggestions
            $random = Episode::where('id', '!=', $episode->id)
                ->where('status', 'published')
                ->where('visibility', 'public')
                ->with(['anime', 'anime.category', 'tags'])
                ->inRandomOrder()
                ->limit(12)
                ->get();

            return view('watch', compact('anime', 'episode', 'random'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning('Episode not found: ' . $slug);
            return redirect()->route('browse')->with('error', 'Episode not found.');
        } catch (\Exception $e) {
            \Log::error('Error in WatchController@index: ' . $e->getMessage());
            return redirect()->route('browse')->with('error', 'Unable to load episode. Please try again later.');
        }
    }

    /**
     * Refresh the watching list for the authenticated user.
     */
    public function refresh()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $watching = VideoProgress::where('user_id', $user->id)
                ->with(['episode', 'episode.anime'])
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
            
            return response()->json($watching);

        } catch (\Exception $e) {
            \Log::error('Error in WatchController@refresh: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to refresh watching list'], 500);
        }
    }

    /**
     * Get anime details for AJAX requests.
     */
    public function getAnimeDetails($id)
    {
        try {
            $anime = Anime::with(['tags', 'category', 'episodes'])
                ->findOrFail($id);

            return response()->json($anime);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Anime not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Error in WatchController@getAnimeDetails: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to load anime details'], 500);
        }
    }
}
