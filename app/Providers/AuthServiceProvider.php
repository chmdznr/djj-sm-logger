<?php

namespace App\Providers;

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
     *
     * Note: Permission-driven Gate definitions have been moved to
     * App\Http\Middleware\AuthGates (runs per-request, after auth).
     * Registering them in boot() here caused a chicken-and-egg failure
     * before the permissions table existed during first migrate.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
