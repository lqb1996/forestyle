<?php

namespace forestyle\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'forestyle\Model' => 'forestyle\Policies\ModelPolicy',
        'forestyle\Post' => 'forestyle\Policies\PostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

//        $permissions = \forestyle\AdminPermission::with('roles')->get();
//        foreach ($permissions as $permission) {
//            Gate::define($permission->name, function($user) use($permission) {
//                return $user->hasPermission($permission);
//            });
//        }
    }
}
