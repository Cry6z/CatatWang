<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // Admin Gates - Full access
        Gate::define('admin-only', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-system-settings', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('view-all-data', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('delete-any-transaction', function (User $user) {
            return $user->role === 'admin';
        });

        // Bendahara Gates - Financial management
        Gate::define('manage-transactions', function (User $user) {
            return in_array($user->role, ['admin', 'bendahara']);
        });

        Gate::define('manage-categories', function (User $user) {
            return in_array($user->role, ['admin', 'bendahara']);
        });

        Gate::define('create-transactions', function (User $user) {
            return in_array($user->role, ['admin', 'bendahara']);
        });

        Gate::define('edit-transactions', function (User $user) {
            return in_array($user->role, ['admin', 'bendahara']);
        });

        Gate::define('export-data', function (User $user) {
            return in_array($user->role, ['admin', 'bendahara']);
        });

        // Anggota Gates - View only
        Gate::define('view-dashboard', function (User $user) {
            return in_array($user->role, ['admin', 'bendahara', 'anggota']);
        });

        Gate::define('view-reports', function (User $user) {
            return in_array($user->role, ['admin', 'bendahara', 'anggota']);
        });

        Gate::define('view-transactions', function (User $user) {
            return in_array($user->role, ['admin', 'bendahara', 'anggota']);
        });

        // Specific transaction ownership
        Gate::define('edit-own-transaction', function (User $user, $transaction) {
            return $user->id === $transaction->user_id || in_array($user->role, ['admin', 'bendahara']);
        });
    }
}
