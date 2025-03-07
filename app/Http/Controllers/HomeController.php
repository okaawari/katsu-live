<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Anime;
use App\Models\Tag;
use App\Models\VideoWatchProgress;
use Auth;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $animes = Anime::orderBy('created_at', 'desc')->take(10)->get();
        $animes1 = Anime::orderBy('created_at', 'asc')->take(12)->get();
        $animes2 = Anime::orderBy('views', 'desc')->take(12)->get();
        $random = Cache::remember('random', 60, function() {
            return Anime::where('name', 'like', '%1%')
                ->inRandomOrder()
                ->take(10)
                ->get();
        });

        $user = $request->user();

        $watching = VideoWatchProgress::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->with('anime')
            ->take(4)
            ->get();

        return view('home', [
            'animes' => $animes,
            'animes1' => $animes1,
            'animes2' => $animes2,
            'random' => $random,
            'watching' => $watching,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
