<?php

namespace App\Http\Controllers;

use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class UserSessionController extends Controller
{
    /**
     * Record a user login session.
     */
    public function recordLogin(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            
            // Mark previous active sessions as not current
            UserSession::where('user_id', $user->id)
                      ->where('is_current', true)
                      ->update(['is_current' => false]);
            
            // Create a new session
            $agent = new Agent();
            $agent->setUserAgent($request->userAgent());
            
            $deviceType = $this->determineDeviceType($agent);
            
            $session = UserSession::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_type' => $deviceType,
                'login_at' => now(),
                'is_current' => true,
            ]);

            Log::info('User session recorded', [
                'user_id' => $user->id,
                'session_id' => $session->id,
                'device_type' => $deviceType,
                'ip_address' => $request->ip()
            ]);
            
            return redirect()->intended();

        } catch (\Exception $e) {
            Log::error('Error recording user session: ' . $e->getMessage());
            return redirect()->intended()->with('error', 'Unable to record session.');
        }
    }
    
    /**
     * End a specific user session.
     */
    public function endSession($id)
    {
        try {
            $session = UserSession::findOrFail($id);
            
            // Check authorization
            $this->authorize('update', $session);
            
            // Mark session as logged out
            $session->update([
                'logout_at' => now(),
                'is_current' => false,
            ]);

            Log::info('User session ended', [
                'session_id' => $session->id,
                'user_id' => $session->user_id
            ]);
            
            return back()->with('success', 'Session ended successfully');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Session not found: ' . $id);
            return back()->with('error', 'Session not found.');
        } catch (\Exception $e) {
            Log::error('Error ending session: ' . $e->getMessage());
            return back()->with('error', 'Unable to end session.');
        }
    }
    
    /**
     * End all other sessions except the current one.
     */
    public function endOtherSessions()
    {
        try {
            $user = Auth::user();
            
            $endedSessions = UserSession::where('user_id', $user->id)
                                      ->where('is_current', false)
                                      ->whereNull('logout_at')
                                      ->update(['logout_at' => now()]);

            Log::info('Other sessions ended', [
                'user_id' => $user->id,
                'sessions_ended' => $endedSessions
            ]);
            
            return back()->with('success', 'All other sessions ended successfully');

        } catch (\Exception $e) {
            Log::error('Error ending other sessions: ' . $e->getMessage());
            return back()->with('error', 'Unable to end other sessions.');
        }
    }

    /**
     * Get all active sessions for the authenticated user.
     */
    public function getActiveSessions()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $sessions = UserSession::where('user_id', $user->id)
                                  ->whereNull('logout_at')
                                  ->orderBy('login_at', 'desc')
                                  ->get();

            return response()->json($sessions);

        } catch (\Exception $e) {
            Log::error('Error retrieving active sessions: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to retrieve sessions'], 500);
        }
    }

    /**
     * Get session statistics for the authenticated user.
     */
    public function getSessionStats()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $stats = [
                'total_sessions' => UserSession::where('user_id', $user->id)->count(),
                'active_sessions' => UserSession::where('user_id', $user->id)
                                               ->whereNull('logout_at')
                                               ->count(),
                'current_session' => UserSession::where('user_id', $user->id)
                                               ->where('is_current', true)
                                               ->first(),
                'recent_sessions' => UserSession::where('user_id', $user->id)
                                               ->orderBy('login_at', 'desc')
                                               ->take(5)
                                               ->get()
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            Log::error('Error retrieving session stats: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to retrieve session statistics'], 500);
        }
    }

    /**
     * Determine device type based on user agent.
     */
    private function determineDeviceType(Agent $agent): string
    {
        if ($agent->isDesktop()) {
            return 'Desktop';
        } elseif ($agent->isTablet()) {
            return 'Tablet';
        } elseif ($agent->isMobile()) {
            return 'Mobile';
        }
        
        return 'Unknown';
    }
}
