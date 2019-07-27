<?php

namespace Ollieread\Users;

use Exception;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use Ollieread\Core\Support\Routes;
use Ollieread\Users\Models\Role;
use Ollieread\Users\Models\User;
use Ollieread\Users\Routes\AdminRoutes;
use Ollieread\Users\Routes\UserRoutes;

class UserServiceProvider extends ServiceProvider
{
    private $userRoleCache = [];

    public function boot(): void
    {
        try {
            $gate  = $this->app->make(Gate::class);
            $roles = Role::all();

            foreach ($roles as $role) {
                $gate->define($role->ident, function (User $user) use ($role) {
                    if (isset($this->userRoleCache[$user->id][$role->ident])) {
                        return $this->userRoleCache[$user->id][$role->ident];
                    }

                    $this->userRoleCache[$user->id][$role->ident] = $user->roles()->where('ident', '=', $role->ident)->first() !== null;

                    return $this->userRoleCache[$user->id][$role->ident];
                });
            }
        } catch (Exception $exception) {
            report($exception);
        }
    }

    public function register(): void
    {
        $routes = $this->app->make(Routes::class);

        if ($routes) {
            $routes->addWebRoutes(UserRoutes::class)
                   ->addWebRoutes(AdminRoutes::class);
        }
    }
}
