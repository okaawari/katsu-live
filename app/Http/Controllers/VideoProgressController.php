<?php

namespace App\Http\Controllers;

use App\Models\VideoProgress;
use App\Models\EpisodeList;
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
            // Update or create video progress
            $progress = VideoProgress::updateOrCreate(
                [
                    'user_id'    => $user->id,
                    'episode_id' => $request->input('episode_id'),
                ],
                [
                    'current_time' => $request->input('current_time'),
                    'duration'     => $request->input('duration'),
                ]
            );

            // Cache in Redis for faster access
            $cacheKey = $this->buildRedisKey($user->id, $request->input('episode_id'));
            Redis::setex($cacheKey, 3600, $request->input('current_time')); // Cache for 1 hour

            // Add to episode list for users with roles other than "User"
            $this->handleEpisodeListForNonUsers($user, $request->input('episode_id'), $progress);

            return response()->json([
                'message' => 'Progress saved successfully',
                'data'    => $progress,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error saving video progress', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'episode_id' => $request->input('episode_id')
            ]);

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Retrieve the video progress for the authenticated user.
     */
    public function getProgress($episodeId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // Try to get from Redis cache first
            $cacheKey = $this->buildRedisKey($user->id, $episodeId);
            $cachedTime = Redis::get($cacheKey);

            if ($cachedTime !== null) {
                return response()->json(['current_time' => (float) $cachedTime], 200);
            }

            // Fallback to database
            $progress = VideoProgress::where('user_id', $user->id)
                ->where('episode_id', $episodeId)
                ->first();

            $time = $progress ? $progress->current_time : 0;

            // Cache the result for next time
            Redis::setex($cacheKey, 3600, $time);

            \Log::info('Retrieved progress from DB', [
                'episode_id'   => $episodeId,
                'user_id'      => $user->id,
                'current_time' => $time,
            ]);

            return response()->json(['current_time' => (float) $time], 200);

        } catch (\Exception $e) {
            \Log::error('Error retrieving video progress', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'episode_id' => $episodeId
            ]);

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Delete video progress for the authenticated user.
     */
    public function destroy($episodeId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // Find and delete from database
            $progress = VideoProgress::where('user_id', $user->id)
                ->where('episode_id', $episodeId)
                ->first();

            if (!$progress) {
                return response()->json([
                    'message' => 'No progress record found for this episode.',
                ], 404);
            }

            $progress->delete();

            // Remove from Redis cache
            $cacheKey = $this->buildRedisKey($user->id, $episodeId);
            Redis::del($cacheKey);

            return response()->json([
                'message' => 'Progress deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error deleting video progress', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'episode_id' => $episodeId
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
            $progress = VideoProgress::where('user_id', $user->id)
                ->with(['episode', 'episode.anime'])
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
     * Handle adding episodes to episode list for users with roles other than "User"
     */
    protected function handleEpisodeListForNonUsers($user, $episodeId, $progress)
    {
        // Check if user has roles other than "User"
        if (!$this->hasNonUserRole($user)) {
            return;
        }

        try {
            // Check if episode is already in the user's episode list
            $existingEntry = EpisodeList::where('user_id', $user->id)
                ->where('episode_id', $episodeId)
                ->first();

            if (!$existingEntry) {
                // Add episode to the list with "watching" status
                EpisodeList::create([
                    'user_id' => $user->id,
                    'episode_id' => $episodeId,
                    'status' => 'watching',
                    'watch_count' => 1,
                    'started_at' => now(),
                    'last_watched_at' => now(),
                ]);

                \Log::info('Added episode to list for non-user role', [
                    'user_id' => $user->id,
                    'episode_id' => $episodeId,
                    'roles' => $user->roles->pluck('name')
                ]);
            } else {
                // Update existing entry
                $existingEntry->update([
                    'last_watched_at' => now(),
                    'watch_count' => $existingEntry->watch_count + 1,
                ]);

                // If progress shows completion (90% or more), mark as completed
                if ($progress->isCompleted() && $existingEntry->status !== 'completed') {
                    $existingEntry->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);
                }
            }

        } catch (\Exception $e) {
            \Log::error('Error handling episode list for non-user role', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'episode_id' => $episodeId
            ]);
        }
    }

    /**
     * Check if user has any roles other than "User"
     */
    protected function hasNonUserRole($user): bool
    {
        // Get all user roles
        $userRoles = $user->roles->pluck('name')->toArray();
        
        // If user has no roles, treat as basic user
        if (empty($userRoles)) {
            return false;
        }

        // If user only has "User" role, return false
        if (count($userRoles) === 1 && in_array('User', $userRoles)) {
            return false;
        }

        // If user has any role other than "User", return true
        $nonUserRoles = array_filter($userRoles, function($role) {
            return strtolower($role) !== 'user';
        });

        return !empty($nonUserRoles);
    }

    /**
     * Build a unique Redis key for storing user + episode progress.
     */
    protected function buildRedisKey(int $userId, int $episodeId): string
    {
        return "video_progress:{$userId}:{$episodeId}";
    }
}