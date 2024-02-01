<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('admin-table-1', function (User $user) {
            return $user->role == 'Admin Table 1';
        });
        Gate::define('admin-table-2', function (User $user) {
            return $user->role == 'Admin Table 2';
        });
        Gate::define('admin-table-3', function (User $user) {
            return $user->role == 'Admin Table 3';
        });
        Gate::define('admin-table-4', function (User $user) {
            return $user->role == 'Admin Table 4';
        });
        Gate::define('admin-monitoring-all', function (User $user) {
            return $user->role == 'Admin Monitoring All';
        });
    }
}
