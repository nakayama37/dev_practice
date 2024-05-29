<?php

namespace App\Providers;

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
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //オーナー権限
        Gate::define('admin', function($user){ 
        return $user->role === 1; 
        }); 
        //管理者権限
        Gate::define('manager-higher', function($user){ 
        return $user->role > 0 && $user->role <= 5; 
        }); 
        // 利用者権限
        Gate::define('user-higher', function($user){ 
        return $user->role > 0 && $user->role <= 9; 
        });
    }
}
