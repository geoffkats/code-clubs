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
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Allow super_admin to access all routes
        if ($user->user_role === 'super_admin') {
            return $next($request);
        }
        
        // Split roles by comma and check if user has any of the required roles
        $allowedRoles = array_map('trim', explode(',', $roles));
        
        // Check if user has any of the required roles
        if (!in_array($user->user_role, $allowedRoles)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
