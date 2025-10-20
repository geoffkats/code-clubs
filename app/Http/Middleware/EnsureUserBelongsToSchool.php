<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserBelongsToSchool
{
	public function handle(Request $request, Closure $next)
	{
		$user = $request->user();
		if (!$user) {
			abort(403, 'User must be authenticated');
		}
		
		// Allow admin users without school restriction
		if ($user->user_role === 'admin' || $user->user_role === 'super_admin') {
			return $next($request);
		}
		
		// Regular users must belong to a school
		if (!$user->school_id) {
			abort(403, 'User must belong to a school');
		}
		
		return $next($request);
	}
}


