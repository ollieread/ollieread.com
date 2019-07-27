<?php

namespace Ollieread\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Ollieread\Core\Support\Routes;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * Define the routes for the application.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function map()
    {
        $routes = $this->app->make(Routes::class);

        if ($routes) {
            $routes->map();
        }
    }
}
