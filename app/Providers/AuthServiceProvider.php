<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('owner', function (User $user, string $id) {
            return $user->id === $id;
        });

        Gate::define('user_admin_mandabem', function () {
            return auth()->user()->user_group_id === 3;
        });

        Gate::define('users', function () {
            return auth()->user()->user_group_id !== 3;
        });
    }
}
