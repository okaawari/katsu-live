<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anime;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class WatchController extends Controller
{
    public function index(Anime $anime, $id) 
    {
        $anime = Anime::find($id);

        $redisKey = "anime:{$anime->id}:views:".Carbon::now()->format('Y-m-d');

        Redis::incr($redisKey);
        
        $random = Anime::inRandomOrder()->limit(12)->get();

        return view('watch', ['anime' => $anime, 'random' => $random]);
    }
}
