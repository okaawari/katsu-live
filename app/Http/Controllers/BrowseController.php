<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Anime;
use App\Models\Tag;

class BrowseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch filter options
        $studios = Anime::select('studio')
            ->whereNotNull('studio')
            ->distinct()
            ->pluck('studio');

        $years = Anime::selectRaw('DISTINCT YEAR(aired_at) as year')
            ->whereNotNull('aired_at')
            ->whereRaw('YEAR(aired_at) IS NOT NULL')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $tags = Tag::select('name')->orderBy('name')->pluck('name')->toArray();

        $animes = Anime::orderBy('created_at', 'desc')->paginate(24);

        // Return view with data
        return view('browse', compact('animes', 'studios', 'years', 'tags'));
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
