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
        // The request is already validated in the FormRequest
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // 1. Update or create in DB
            $progress = VideoWatchProgress::updateOrCreate(
                [
                    'user_id'  => $user->id,
                    'animes_id' => $request->input('animes_id'),
                ],
                [
                    'current_time' => $request->input('current_time'),
                ]
            );

            // 2. Also store (cache) in Redis
            // $cacheKey = $this->buildRedisKey($user->id, $request->input('animes_id'));
            // Redis::set($cacheKey, $request->input('current_time'));

            return response()->json([
                'message' => 'Progress saved successfully',
                'data'    => $progress,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error saving video progress', ['error' => $e->getMessage()]);

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

        // 1. Check Redis first
        $cacheKey = $this->buildRedisKey($user->id, $animeId);
        $cachedTime = Redis::get($cacheKey);

        if (!is_null($cachedTime)) {
            \Log::info('Retrieved progress from Redis', [
                'animes_id'     => $animeId,
                'user_id'      => $user->id,
                'current_time' => $cachedTime,
            ]);

            return response()->json(['current_time' => (float) $cachedTime], 200);
        }

        // 2. Fallback to DB if Redis cache doesn’t exist
        $progress = VideoWatchProgress::where('user_id', $user->id)
            ->where('animes_id', $animeId)
            ->first();

        $time = $progress ? $progress->current_time : 0;

        \Log::info('Retrieved progress from DB', [
            'animes_id'     => $animeId,
            'user_id'      => $user->id,
            'current_time' => $time,
        ]);

        // 3. Optionally, cache it in Redis for next time
        Redis::set($cacheKey, $time);

        return response()->json(['current_time' => (float) $time], 200);
    }

    /**
     * Build a unique Redis key for storing user + anime progress.
     */
    protected function buildRedisKey(int $userId, int $animeId): string
    {
        return "video_progress:{$userId}:{$animeId}";
    }

    public function destroy($animeId)
    {
        // Check if user is authenticated
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find the user’s progress
        $progress = VideoWatchProgress::where('user_id', $user->id)
            ->where('animes_id', $animeId)
            ->first();

        if (!$progress) {
            return response()->json([
                'message' => 'No progress record found for this anime.',
            ], 404);
        }

        // Delete from DB
        $progress->delete();

        // OPTIONAL: If you are also caching progress in Redis, remove that key
        $cacheKey = "video_progress:{$user->id}:{$animeId}";
        Redis::del($cacheKey);

        return response()->json([
            'message' => 'Progress deleted successfully.',
        ], 200);
    }
}
