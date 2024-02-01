<?php

namespace App\Providers;

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
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $tables = ['1', '2', '3', '4'];

        foreach ($tables as $table) {
            Gate::define("admin-table-$table", fn (User $user) => $user->role == "Admin Table $table");

            Gate::define("admin-table-$table-monitoring-all", fn (User $user) => $user->role == "Admin Table $table" || $user->role == 'Admin Monitoring All');
        }

        Gate::define('admin-monitoring-all', fn (User $user) => $user->role == 'Admin Monitoring All');

        Gate::define('admin-table', function (User $user) use ($tables) {
            $tableRoles = array_map(fn ($table) => "Admin Table $table", $tables);
            return in_array($user->role, $tableRoles);
        });
    }
}
