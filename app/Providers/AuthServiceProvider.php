<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Role;
use App\Models\Permission;

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
        $this->registerPolicies();
        //
        $permissions = Permission::all();

        foreach ($permissions as $permission) {

            Gate::define($permission->title, function ($user) use ($permission) {
                $a = $user->roles()->first()->permissions()->where('title', $permission->title);
                return is_null($a->first()) ? 0 : 1;
            });

        }
    }
}
