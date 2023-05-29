<?php

namespace App\Providers;

 use App\Models\Client;use App\Models\Developer;use App\Models\Project;use App\Models\WorkLog;use App\Policies\ClientPolicy;use App\Policies\DeveloperPolicy;use App\Policies\ProjectPolicy;use App\Policies\WorkLogPolicy;use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Client::class => ClientPolicy::class,
        Project::class => ProjectPolicy::class,
        Developer::class => DeveloperPolicy::class,
        WorkLog::class => WorkLogPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
//
//        // Implicitly grant "admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
