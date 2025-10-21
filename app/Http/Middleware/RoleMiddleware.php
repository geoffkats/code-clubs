<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Allow super_admin to access all routes
        if ($user->user_role === 'super_admin') {
            return $next($request);
        }
        
        // Allow admin users to access admin routes
        if ($role === 'admin' && $user->user_role === 'admin') {
            return $next($request);
        }
        
        // Check if user has the required role
        if ($user->user_role !== $role) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
