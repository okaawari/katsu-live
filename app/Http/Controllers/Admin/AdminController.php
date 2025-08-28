<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use App\Models\Episode;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_anime' => Anime::count(),
            'total_episodes' => Episode::count(),
            'total_users' => User::count(),
            'published_anime' => Anime::whereNotNull('published_at')->count(),
            'published_episodes' => Episode::where('status', 'published')->count(),
            'scheduled_episodes' => Episode::where('status', 'scheduled')->count(),
        ];

        $recent_anime = Anime::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recent_episodes = Episode::with('anime')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_anime', 'recent_episodes'));
    }
}