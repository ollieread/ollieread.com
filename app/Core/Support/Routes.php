<?php

namespace Ollieread\Core\Support;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class Routes
{
    protected $webRoutes = [];
    protected $apiRoutes = [];

    public function addApiRoutes(string $routeClass, ?string $domain = null)
    {
        $this->apiRoutes[$domain ?? env('APP_DOMAIN')][] = $routeClass;
        return $this;
    }

    public function addWebRoutes(string $routeClass, ?string $domain = null)
    {
        $this->webRoutes[$domain ?? env('APP_DOMAIN')][] = $routeClass;
        return $this;
    }

    public function map(): void
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
             ->group(function (Router $router) {
                 foreach ($this->apiRoutes as $domain => $routes) {
                     $router->domain($domain)->group(static function (Router $router) use ($routes) {
                         array_walk($routes, static function (string $routeClass) use ($router) {
                             (new $routeClass())($router);
                         });
                     });
                 }
             });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
             ->group(function (Router $router) {
                 foreach ($this->webRoutes as $domain => $routes) {
                     $router->domain($domain)->group(static function (Router $router) use ($routes) {
                         array_walk($routes, static function (string $routeClass) use ($router) {
                             $routes = new $routeClass;

                             if ($routes instanceof Contracts\Routes) {
                                 $router->group([
                                     'prefix' => $routes->prefix(),
                                     'as'     => $routes->name(),
                                 ], static function (Router $router) use ($routes) {
                                     return $routes($router);
                                 });
                             } else {
                                 $routes($router);
                             }
                         });
                     });
                 }
             });
    }
}
