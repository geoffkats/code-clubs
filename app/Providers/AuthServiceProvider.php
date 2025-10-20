<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Club;
use App\Models\Report;
use App\Models\LessonNote;
use App\Models\ClubSession;
use App\Policies\UserPolicy;
use App\Policies\ClubPolicy;
use App\Policies\ReportPolicy;
use App\Policies\LessonNotePolicy;
use App\Policies\ClubSessionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Club::class => ClubPolicy::class,
        Report::class => ReportPolicy::class,
        LessonNote::class => LessonNotePolicy::class,
        ClubSession::class => ClubSessionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define additional gates if needed
        Gate::define('viewClub', [ClubPolicy::class, 'viewClub']);
        Gate::define('create', [LessonNotePolicy::class, 'create']);
        Gate::define('view', [LessonNotePolicy::class, 'view']);
        Gate::define('update', [LessonNotePolicy::class, 'update']);
        Gate::define('delete', [LessonNotePolicy::class, 'delete']);
        Gate::define('approveFacilitator', [ReportPolicy::class, 'approveFacilitator']);
        Gate::define('approveAdmin', [ReportPolicy::class, 'approveAdmin']);
    }
}
