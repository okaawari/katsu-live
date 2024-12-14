<?php

namespace App\Http\Controllers;

use App\Models\VideoWatchProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoProgressController extends Controller
{
    // Save video progress
    public function saveProgress(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'animes_id' => 'required|integer',  // Ensure video exists
            'current_time' => 'required|numeric',        // Ensure current_time is numeric
        ]);

        // Get the authenticated user
        $user = Auth::user();

        $userId = auth()->id();

        // If user is not authenticated, return an error
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // Save the progress for the authenticated user
            $progress = VideoWatchProgress::updateOrCreate(
                ['user_id' => $user->id, 'animes_id' => $request->animes_id],
                ['current_time' => $request->current_time]
            );

            return response()->json(['message' => 'Progress saved successfully', 'data' => $progress]);
        } catch (\Exception $e) {
            // Log the error and return a response
            \Log::error('Error saving video progress', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }


    // Retrieve video progress
    public function getProgress($animeId)
    {
        $userId = auth()->id();

        $progress = VideoWatchProgress::where('user_id', $userId)
            ->where('animes_id', $animeId)
            ->first();

        if ($progress) {
            \Log::info('Retrieved progress', [
                'anime_id' => $animeId,
                'user_id' => $userId,
                'current_time' => $progress->current_time,
            ]);
            return response()->json([
                'current_time' => $progress->current_time,
            ]);
        }

        \Log::info('No progress found', [
            'anime_id' => $animeId,
            'user_id' => $userId,
        ]);

        return response()->json([
            'current_time' => 0,
        ]);
    }


}
