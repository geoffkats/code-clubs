<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserBelongsToSchool
{
	public function handle(Request $request, Closure $next)
	{
		$user = $request->user();
		if (!$user || !$user->school_id) {
			abort(403, 'User must belong to a school');
		}
		return $next($request);
	}
}


