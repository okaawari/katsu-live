<?php

namespace App\Http\Controllers;

use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class UserSessionController extends Controller
{
    /**
     * Record a user login session.
     */
    public function recordLogin(Request $request)
    {
        $user = Auth::user();
        
        // If there was a previous active session for this user, mark it as not current
        UserSession::where('user_id', $user->id)
                  ->where('is_current', true)
                  ->update(['is_current' => false]);
        
        // Create a new session
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());
        
        $deviceType = 'Unknown';
        if ($agent->isDesktop()) {
            $deviceType = 'Desktop';
        } elseif ($agent->isTablet()) {
            $deviceType = 'Tablet';
        } elseif ($agent->isMobile()) {
            $deviceType = 'Mobile';
        }
        
        UserSession::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_type' => $deviceType,
            'login_at' => now(),
            'is_current' => true,
        ]);
        
        return redirect()->intended();
    }
    
    /**
     * End a user session.
     */
    public function endSession($id)
    {
        $session = UserSession::findOrFail($id);
        
        // Check authorization
        $this->authorize('update', $session);
        
        // Mark session as logged out
        $session->update([
            'logout_at' => now(),
            'is_current' => false,
        ]);
        
        return back()->with('status', 'Session ended successfully');
    }
    
    /**
     * End all other sessions except the current one.
     */
    public function endOtherSessions()
    {
        $user = Auth::user();
        
        UserSession::where('user_id', $user->id)
                  ->where('is_current', false)
                  ->whereNull('logout_at')
                  ->update(['logout_at' => now()]);
        
        return back()->with('status', 'All other sessions ended successfully');
    }
}
