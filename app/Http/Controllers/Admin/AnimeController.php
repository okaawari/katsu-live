<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Anime::with(['category', 'tags']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_english', 'like', "%{$search}%")
                  ->orWhere('title_japanese', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $anime = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::all();

        return view('admin.anime.index', compact('anime', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::active()->ordered()->get();
        return view('admin.anime.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_english' => 'nullable|string|max:255',
            'title_japanese' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:ongoing,completed,upcoming,cancelled',
            'total_episodes' => 'nullable|integer|min:1',
            'visibility' => 'required|in:public,private,draft',
            'is_featured' => 'boolean',
            'cover_image' => 'nullable|image|max:2048',
            'poster' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);
        $validated['author_id'] = auth()->id();

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('anime/covers', 'public');
        }

        // Handle poster upload  
        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $anime = Anime::create($validated);

        // Attach tags
        if ($request->has('tags')) {
            $anime->tags()->attach($request->tags);
        }

        return redirect()->route('admin.anime.index')
            ->with('success', 'Anime created successfully!');
    }

    public function show(Anime $anime)
    {
        $anime->load(['category', 'tags', 'episodes']);
        return view('admin.anime.show', compact('anime'));
    }

    public function edit(Anime $anime)
    {
        $categories = Category::all();
        $tags = Tag::active()->ordered()->get();
        $anime->load('tags');
        return view('admin.anime.edit', compact('anime', 'categories', 'tags'));
    }

    public function update(Request $request, Anime $anime)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_english' => 'nullable|string|max:255',
            'title_japanese' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:ongoing,completed,upcoming,cancelled',
            'total_episodes' => 'nullable|integer|min:1',
            'visibility' => 'required|in:public,private,draft',
            'is_featured' => 'boolean',
            'cover_image' => 'nullable|image|max:2048',
            'poster' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Update slug if title changed
        if ($anime->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($anime->cover_image) {
                Storage::disk('public')->delete($anime->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('anime/covers', 'public');
        }

        // Handle poster upload
        if ($request->hasFile('poster')) {
            // Delete old poster
            if ($anime->poster) {
                Storage::disk('public')->delete($anime->poster);
            }
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $anime->update($validated);

        // Sync tags
        if ($request->has('tags')) {
            $anime->tags()->sync($request->tags);
        } else {
            $anime->tags()->detach();
        }

        return redirect()->route('admin.anime.index')
            ->with('success', 'Anime updated successfully!');
    }

    public function destroy(Anime $anime)
    {
        // Delete associated images
        if ($anime->cover_image) {
            Storage::disk('public')->delete($anime->cover_image);
        }
        if ($anime->poster) {
            Storage::disk('public')->delete($anime->poster);
        }

        $anime->delete();

        return redirect()->route('admin.anime.index')
            ->with('success', 'Anime deleted successfully!');
    }
}
