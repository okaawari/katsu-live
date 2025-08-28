<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Episode;
use App\Models\Tag;
use App\Models\Category;

class BrowseController extends Controller
{
    /**
     * Display a paginated listing of episodes with filtering options.
     */
    public function index(Request $request)
    {
        try {
            // Fetch filter options
            $years = Episode::selectRaw('DISTINCT YEAR(published_at) as year')
                ->whereNotNull('published_at')
                ->whereRaw('YEAR(published_at) IS NOT NULL')
                ->where('status', 'published')
                ->where('visibility', 'public')
                ->orderBy('year', 'desc')
                ->pluck('year');

            $tags = Tag::select('name_mn')
                ->whereNotNull('name_mn')
                ->where('name_mn', '!=', '')
                ->whereHas('episodes', function($q) {
                    $q->where('status', 'published')
                      ->where('visibility', 'public');
                })
                ->distinct()
                ->orderBy('id', 'asc')
                ->pluck('name_mn');

            $categories = Category::select('id', 'name')
                ->whereHas('animes.episodes', function($q) {
                    $q->where('status', 'published')
                      ->where('visibility', 'public');
                })
                ->orderBy('name')
                ->get();

            // Build query with filters
            $query = Episode::query();

            // Only show published and public episodes
            $query->where('status', 'published')
                  ->where('visibility', 'public');

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('title_english', 'like', "%{$search}%")
                      ->orWhere('title_japanese', 'like', "%{$search}%")
                      ->orWhere('synopsis', 'like', "%{$search}%")
                      ->orWhereHas('anime', function($animeQuery) use ($search) {
                          $animeQuery->where('title', 'like', "%{$search}%")
                                    ->orWhere('title_english', 'like', "%{$search}%")
                                    ->orWhere('title_japanese', 'like', "%{$search}%");
                      });
                });
            }

            if ($request->filled('year')) {
                $query->whereYear('published_at', $request->get('year'));
            }

            if ($request->filled('tags')) {
                $tags = explode(',', $request->get('tags'));
                $query->whereHas('tags', function($q) use ($tags) {
                    $q->whereIn('name_mn', $tags);
                });
            }

            if ($request->filled('category')) {
                $query->whereHas('anime', function($q) {
                    $q->where('category_id', $request->get('category'));
                });
            }

            // Apply sorting
            $sortBy = $request->get('sort', 'published_at');
            $sortOrder = $request->get('order', 'desc');
            
            $allowedSortFields = ['published_at', 'created_at', 'title', 'episode_number', 'view_count', 'average_rating'];
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'published_at';
            }

            $query->orderBy($sortBy, $sortOrder);

            // Paginate results - this must be done on the query builder, not collection
            $episodes = $query->with(['anime', 'anime.category', 'tags'])
                ->paginate(24)
                ->withQueryString();

            return view('browse', compact('episodes', 'years', 'tags', 'categories'));

        } catch (\Exception $e) {
            \Log::error('Error in BrowseController@index: ' . $e->getMessage());
            
            // Create an empty paginator for error case
            $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                24,
                1,
                ['path' => request()->url()]
            );
            
            return view('browse', [
                'episodes' => $emptyPaginator,
                'years' => collect(),
                'tags' => [],
                'categories' => collect(),
            ])->with('error', 'Unable to load browse content. Please try again later.');
        }
    }

    /**
     * Get episode suggestions for search autocomplete.
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Episode::where('status', 'published')
            ->where('visibility', 'public')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('title_english', 'like', "%{$query}%")
                  ->orWhere('title_japanese', 'like', "%{$query}%")
                  ->orWhereHas('anime', function($animeQuery) use ($query) {
                      $animeQuery->where('title', 'like', "%{$query}%")
                                ->orWhere('title_english', 'like', "%{$query}%")
                                ->orWhere('title_japanese', 'like', "%{$query}%");
                  });
            })
            ->select('id', 'title', 'title_english', 'title_japanese', 'synopsis', 'anime_id')
            ->with('anime:id,title,title_english,title_japanese')
            ->limit(10)
            ->get();

        return response()->json($suggestions);
    }
}
