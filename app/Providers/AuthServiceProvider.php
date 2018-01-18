<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Rol;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('show-log', function ($user, $actividad) {
            return $user->id == $actividad->user_id
            ||$user->roles_id == Rol::roleId('Administrador')
            ||$user->roles_id == Rol::roleId('Supervisor');
        });

        Gate::define('show-activity', function($user, $id) {
            return $user->id == $id
            ||$user->roles_id == Rol::roleId('Administrador')
            ||$user->roles_id == Rol::roleId('Supervisor');
        });

        Gate::define('show-profile', function($user, $usuario) {
            return $user->id === $usuario->user_id 
                || $usuario->user->roles_id === Rol::roleId('Proveedor') 
                || $user->roles_id === Rol::roleId('Administrador')
                || $user->roles_id === Rol::roleId('Supervisor');
        });

    }
}
