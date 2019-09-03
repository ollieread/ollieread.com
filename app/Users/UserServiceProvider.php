<?php

namespace Ollieread\Users;

use Exception;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;
use Ollieread\Core\Support\Routes;
use Ollieread\Users\Models\User;
use Ollieread\Users\Operations\GetUserPermissions;
use Ollieread\Users\Routes\AdminRoutes;
use Ollieread\Users\Routes\UserRoutes;
use Ollieread\Users\Support\Permissions;
use SocialiteProviders\Discord\Provider;

class UserServiceProvider extends ServiceProvider
{
    private $userPermissionCache = [];

    public function boot(): void
    {
        try {
            $gate = $this->app->make(Gate::class);

            foreach (Permissions::ALL as $key => $value) {
                $gate->define($key, function (User $user) use ($value) {
                    if (! isset($this->userPermissionCache[$user->getKey()])) {
                        $this->userPermissionCache[$user->getKey()] = (new GetUserPermissions)
                            ->setUser($user)
                            ->perform();
                    }

                    return (bool)($this->userPermissionCache[$user->getKey()] & Permissions::ADMIN_MASTER)
                        || (bool)($this->userPermissionCache[$user->getKey()] & $value);
                });
            }

            $socialite = $this->app->make(Factory::class);
            $socialite->extend('discord', static function () use ($socialite) {
                return $socialite->buildProvider(Provider::class, config('services.discord'));
            });
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
