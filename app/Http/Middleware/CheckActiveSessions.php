<?php

namespace App\Http\Middleware;

use App\Models\UserSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveSessions
{
    /**
     * Maximum number of active sessions allowed per user
     */
    protected $maxActiveSessions = 5;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Count active sessions (excluding the current one)
            $activeSessionsCount = UserSession::where('user_id', $user->id)
                ->where('is_current', false)
                ->whereNull('logout_at')
                ->count();
            
            // If user has more than the maximum allowed active sessions
            if ($activeSessionsCount >= $this->maxActiveSessions) {
                // Log out the user
                Auth::logout();
                
                // Store a message in the session
                session()->flash('error', 'You have been logged out because you have exceeded the maximum number of active sessions (5). Please log in again.');
                
                // Redirect to login page
                return redirect()->route('login');
            }
        }
        
        return $next($request);
    }
}
