<?php

namespace App\Http\Controllers;

use App\Models\VideoWatchProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\StoreVideoProgressRequest;

class VideoProgressController extends Controller
{
    /**
     * Store or update the video progress for the authenticated user.
     */
    public function saveProgress(StoreVideoProgressRequest $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // Update or create in database
            $progress = VideoWatchProgress::updateOrCreate(
                [
                    'user_id'  => $user->id,
                    'animes_id' => $request->input('animes_id'),
                ],
                [
                    'current_time' => $request->input('current_time'),
                ]
            );

            // Cache in Redis for faster access
            $cacheKey = $this->buildRedisKey($user->id, $request->input('animes_id'));
            Redis::setex($cacheKey, 3600, $request->input('current_time')); // Cache for 1 hour

            return response()->json([
                'message' => 'Progress saved successfully',
                'data'    => $progress,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error saving video progress', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'anime_id' => $request->input('animes_id')
            ]);

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Retrieve the video progress for the authenticated user.
     */
    public function getProgress($animeId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // Try to get from Redis cache first
            $cacheKey = $this->buildRedisKey($user->id, $animeId);
            $cachedTime = Redis::get($cacheKey);

            if ($cachedTime !== null) {
                return response()->json(['current_time' => (float) $cachedTime], 200);
            }

            // Fallback to database
            $progress = VideoWatchProgress::where('user_id', $user->id)
                ->where('animes_id', $animeId)
                ->first();

            $time = $progress ? $progress->current_time : 0;

            // Cache the result for next time
            Redis::setex($cacheKey, 3600, $time);

            \Log::info('Retrieved progress from DB', [
                'animes_id'     => $animeId,
                'user_id'      => $user->id,
                'current_time' => $time,
            ]);

            return response()->json(['current_time' => (float) $time], 200);

        } catch (\Exception $e) {
            \Log::error('Error retrieving video progress', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'anime_id' => $animeId
            ]);

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Delete video progress for the authenticated user.
     */
    public function destroy($animeId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // Find and delete from database
            $progress = VideoWatchProgress::where('user_id', $user->id)
                ->where('animes_id', $animeId)
                ->first();

            if (!$progress) {
                return response()->json([
                    'message' => 'No progress record found for this anime.',
                ], 404);
            }

            $progress->delete();

            // Remove from Redis cache
            $cacheKey = $this->buildRedisKey($user->id, $animeId);
            Redis::del($cacheKey);

            return response()->json([
                'message' => 'Progress deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error deleting video progress', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'anime_id' => $animeId
            ]);

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Get all progress records for the authenticated user.
     */
    public function getAllProgress()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $progress = VideoWatchProgress::where('user_id', $user->id)
                ->with('anime')
                ->orderBy('updated_at', 'desc')
                ->get();

            return response()->json($progress);

        } catch (\Exception $e) {
            \Log::error('Error retrieving all progress', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Build a unique Redis key for storing user + anime progress.
     */
    protected function buildRedisKey(int $userId, int $animeId): string
    {
        return "video_progress:{$userId}:{$animeId}";
    }
}
