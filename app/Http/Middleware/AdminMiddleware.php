<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // You can customize this logic based on your user roles/permissions system
        // For now, we'll allow all authenticated users to access admin
        // You might want to check for a specific role or permission
        
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Uncomment and modify this if you have role-based access control
        // if (!auth()->user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized access to admin area.');
        // }

        return $next($request);
    }
}
