<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VideoProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display the user profile page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Get user's watching history
        $watchingHistory = VideoProgress::where('user_id', $user->id)
            ->with('episode.anime')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('user', compact('user', 'watchingHistory'));
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
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'bio' => $request->bio,
            ]);

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
     * Get user statistics.
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
