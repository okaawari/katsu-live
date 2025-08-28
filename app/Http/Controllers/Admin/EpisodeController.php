<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Anime;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EpisodeController extends Controller
{
    public function index(Request $request)
    {
        $query = Episode::with(['anime', 'tags']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('episode_number', $search)
                  ->orWhere('id', $search)
                  ->orWhereHas('anime', function($animeQuery) use ($search) {
                      $animeQuery->where('title', 'like', "%{$search}%")
                               ->orWhere('title_english', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by anime
        if ($request->filled('anime_id')) {
            $query->where('anime_id', $request->anime_id);
        }

        $episodes = $query->orderBy('created_at', 'desc')->paginate(15);
        $anime_list = Anime::orderBy('title')->get();

        return view('admin.episodes.index', compact('episodes', 'anime_list'));
    }

    public function create()
    {
        $anime_list = Anime::orderBy('title')->get();
        $tags = Tag::active()->ordered()->get();
        return view('admin.episodes.create', compact('anime_list', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'anime_id' => 'required|exists:animes,id',
            'episode_number' => 'required|integer|min:1',
            'title' => 'nullable|string|max:255',
            'synopsis' => 'nullable|string',
            'poster_image' => 'nullable|image|max:5120', // 5MB
            'thumbnail_image' => 'nullable|image|max:2048',
            'video_720p' => 'required|file|mimes:mp4,avi,mov,wmv|max:2097152', // 2GB
            'subtitle_mongolian' => 'nullable|file|mimes:vtt,srt',
            'subtitle_english' => 'nullable|file|mimes:vtt,srt',
            'status' => 'required|in:draft,scheduled,published,archived',
            'visibility' => 'required|in:public,private,premium',
            'scheduled_at' => 'nullable|date|after:now',
            'is_featured' => 'boolean',
            'is_premium' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Generate slug
        $anime = Anime::find($validated['anime_id']);
        $validated['slug'] = Str::slug($anime->title . '-episode-' . $validated['episode_number']);
        $validated['uploaded_by'] = auth()->id();

        // Handle file uploads
        if ($request->hasFile('poster_image')) {
            $validated['poster_image'] = $request->file('poster_image')->store('episodes/posters', 'public');
        }

        if ($request->hasFile('thumbnail_image')) {
            $validated['thumbnail_image'] = $request->file('thumbnail_image')->store('episodes/thumbnails', 'public');
        }

        if ($request->hasFile('video_720p')) {
            $validated['video_720p'] = $request->file('video_720p')->store('episodes/videos', 'public');
        }

        if ($request->hasFile('subtitle_mongolian')) {
            $validated['subtitle_mongolian'] = $request->file('subtitle_mongolian')->store('episodes/subtitles', 'public');
        }

        if ($request->hasFile('subtitle_english')) {
            $validated['subtitle_english'] = $request->file('subtitle_english')->store('episodes/subtitles', 'public');
        }

        // Set published_at if status is published
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $episode = Episode::create($validated);

        // Attach tags
        if ($request->has('tags')) {
            $episode->tags()->attach($request->tags);
        }

        return redirect()->route('admin.episodes.index')
            ->with('success', 'Episode created successfully!');
    }

    public function show(Episode $episode)
    {
        $episode->load(['anime', 'tags', 'uploader']);
        return view('admin.episodes.show', compact('episode'));
    }

    public function edit(Episode $episode)
    {
        $anime_list = Anime::orderBy('title')->get();
        $tags = Tag::active()->ordered()->get();
        $episode->load('tags');
        return view('admin.episodes.edit', compact('episode', 'anime_list', 'tags'));
    }

    public function update(Request $request, Episode $episode)
    {
        $validated = $request->validate([
            'anime_id' => 'required|exists:animes,id',
            'episode_number' => 'required|integer|min:1',
            'title' => 'nullable|string|max:255',
            'synopsis' => 'nullable|string',
            'poster_image' => 'nullable|image|max:5120',
            'thumbnail_image' => 'nullable|image|max:2048',
            'video_720p' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:2097152',
            'subtitle_mongolian' => 'nullable|file|mimes:vtt,srt',
            'subtitle_english' => 'nullable|file|mimes:vtt,srt',
            'status' => 'required|in:draft,scheduled,published,archived',
            'visibility' => 'required|in:public,private,premium',
            'scheduled_at' => 'nullable|date|after:now',
            'is_featured' => 'boolean',
            'is_premium' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Update slug if anime or episode number changed
        if ($episode->anime_id !== $validated['anime_id'] || $episode->episode_number !== $validated['episode_number']) {
            $anime = Anime::find($validated['anime_id']);
            $validated['slug'] = Str::slug($anime->title . '-episode-' . $validated['episode_number']);
        }

        // Handle file uploads
        if ($request->hasFile('poster_image')) {
            if ($episode->poster_image) {
                Storage::disk('public')->delete($episode->poster_image);
            }
            $validated['poster_image'] = $request->file('poster_image')->store('episodes/posters', 'public');
        }

        if ($request->hasFile('thumbnail_image')) {
            if ($episode->thumbnail_image) {
                Storage::disk('public')->delete($episode->thumbnail_image);
            }
            $validated['thumbnail_image'] = $request->file('thumbnail_image')->store('episodes/thumbnails', 'public');
        }

        if ($request->hasFile('video_720p')) {
            if ($episode->video_720p) {
                Storage::disk('public')->delete($episode->video_720p);
            }
            $validated['video_720p'] = $request->file('video_720p')->store('episodes/videos', 'public');
        }

        if ($request->hasFile('subtitle_mongolian')) {
            if ($episode->subtitle_mongolian) {
                Storage::disk('public')->delete($episode->subtitle_mongolian);
            }
            $validated['subtitle_mongolian'] = $request->file('subtitle_mongolian')->store('episodes/subtitles', 'public');
        }

        if ($request->hasFile('subtitle_english')) {
            if ($episode->subtitle_english) {
                Storage::disk('public')->delete($episode->subtitle_english);
            }
            $validated['subtitle_english'] = $request->file('subtitle_english')->store('episodes/subtitles', 'public');
        }

        // Set published_at if status is published and not already set
        if ($validated['status'] === 'published' && !$episode->published_at) {
            $validated['published_at'] = now();
        }

        $episode->update($validated);

        // Sync tags
        if ($request->has('tags')) {
            $episode->tags()->sync($request->tags);
        } else {
            $episode->tags()->detach();
        }

        return redirect()->route('admin.episodes.index')
            ->with('success', 'Episode updated successfully!');
    }

    public function destroy(Episode $episode)
    {
        // Delete associated files
        $files = [
            $episode->poster_image,
            $episode->thumbnail_image,
            $episode->video_720p,
            $episode->subtitle_mongolian,
            $episode->subtitle_english
        ];

        foreach ($files as $file) {
            if ($file) {
                Storage::disk('public')->delete($file);
            }
        }

        $episode->delete();

        return redirect()->route('admin.episodes.index')
            ->with('success', 'Episode deleted successfully!');
    }

    public function uploadProgress(Request $request)
    {
        // This endpoint can be used for AJAX upload progress tracking
        return response()->json(['status' => 'uploading']);
    }
}