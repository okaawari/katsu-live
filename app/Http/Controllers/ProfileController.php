<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VideoProgress;
use App\Models\Anime;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display public profile page by user ID.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // Get user's public information
        $stats = [
            'total_watched' => VideoProgress::where('user_id', $user->id)->count(),
            'total_anime' => Anime::where('author_id', $user->id)->count(),
            'total_comments' => Comment::where('user_id', $user->id)->count(),
            'total_ratings' => Rating::where('user_id', $user->id)->count(),
        ];

        // Get recent watching history (public)
        $recentWatching = VideoProgress::where('user_id', $user->id)
            ->with('episode.anime')
            ->orderBy('updated_at', 'desc')
            ->take(6)
            ->get();

        // Get user's anime (if any)
        $userAnime = Anime::where('author_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Check if current user is viewing their own profile
        $isOwnProfile = Auth::check() && Auth::id() == $user->id;

        return view('profile.show', compact('user', 'stats', 'recentWatching', 'userAnime', 'isOwnProfile'));
    }

    /**
     * Display settings page (only for authenticated user).
     */
    public function settings()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Get user's detailed statistics
        $stats = [
            'total_watched' => VideoProgress::where('user_id', $user->id)->count(),
            'total_anime' => Anime::where('author_id', $user->id)->count(),
            'total_comments' => Comment::where('user_id', $user->id)->count(),
            'total_ratings' => Rating::where('user_id', $user->id)->count(),
            'member_since' => $user->created_at->diffForHumans(),
        ];

        // Get recent activity
        $recentActivity = VideoProgress::where('user_id', $user->id)
            ->with('episode.anime')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        // Get payment history
        $paymentHistory = PaymentHistory::where('user_id', $user->id)
            ->orderBy('transaction_date', 'desc')
            ->take(20)
            ->get();

        return view('profile.settings', compact('user', 'stats', 'recentActivity', 'paymentHistory'));
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'cover_image' => ['nullable', 'image', 'max:5120'], // 5MB max
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'bio' => $request->bio,
                'location' => $request->location,
                'website' => $request->website,
                'birth_date' => $request->birth_date,
            ]);

            // Handle cover image upload if provided
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $ext = $file->getClientOriginalExtension();
                $filename = $user->id . '_cover.' . $ext;

                // Use Intervention Image to resize
                $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $cover = $manager->read($file->getRealPath());

                // Resize to optimal cover image dimensions (1200x400) while maintaining aspect ratio
                $cover->resize(1200, 400, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                // Create a canvas with the target dimensions and center the image
                $canvas = $manager->create(1200, 400);
                $canvas->place($cover, 'center');
                $canvas->save(storage_path('app/public/user/cover/').$filename);

                $user->update(['cover_image' => $filename]);
            }

            return back()->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Error updating user profile: ' . $e->getMessage());
            return back()->with('error', 'Unable to update profile. Please try again.');
        }
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $user = Auth::user();
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return back()->with('success', 'Password updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Error updating password: ' . $e->getMessage());
            return back()->with('error', 'Unable to update password. Please try again.');
        }
    }

    /**
     * Get user statistics for AJAX requests.
     */
    public function getStats()
    {
        try {
            $user = Auth::user();
            
            $stats = [
                'total_watched' => VideoProgress::where('user_id', $user->id)->count(),
                'recent_activity' => VideoProgress::where('user_id', $user->id)
                    ->with('episode.anime')
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get(),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            \Log::error('Error getting user stats: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to load statistics'], 500);
        }
    }
}
