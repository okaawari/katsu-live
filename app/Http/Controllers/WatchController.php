<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anime;


class WatchController extends Controller
{
    public function index(Anime $anime, $id) {
        $anime = Anime::find($id);
        
        $random = Anime::inRandomOrder()->limit(12)->get();

        return view('watch', ['anime' => $anime, 'random' => $random]);
    }
}
