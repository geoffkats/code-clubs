<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class StudentViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share upcoming sessions data with student layout
        View::composer('layouts.student', function ($view) {
            if (Auth::guard('student')->check()) {
                $student = Auth::guard('student')->user();
                
                $upcomingSessions = collect();
                foreach ($student->clubs as $club) {
                    $clubSessions = $club->sessions()
                        ->where('session_date', '>=', now())
                        ->orderBy('session_date')
                        ->take(3)
                        ->get();
                    $upcomingSessions = $upcomingSessions->merge($clubSessions);
                }
                
                // Sort by session date and take the first 3
                $upcomingSessions = $upcomingSessions->sortBy('session_date')->take(3);
                
                $view->with('upcomingSessions', $upcomingSessions);
            }
        });
    }
}
