<?php

namespace Ollieread\Admin;

use Illuminate\Support\ServiceProvider;
use Ollieread\Admin\Routes\AdminRoutes;
use Ollieread\Core\Support\Routes;

class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $routes = $this->app->make(Routes::class);

        if ($routes) {
            $routes->addWebRoutes(AdminRoutes::class);
        }
    }
}
