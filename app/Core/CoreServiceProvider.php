<?php

namespace Ollieread\Core;

use BotMan\BotMan\BotMan;
use Illuminate\Auth\SessionGuard;
use Illuminate\Container\Container;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Ollieread\Core\Routes\CoreRoutes;
use Ollieread\Core\Support\Routes;
use Ollieread\Users\Models\User;

class CoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        $routes = new Routes;
        $routes->addWebRoutes(CoreRoutes::class);
        $this->app->instance(Routes::class, $routes);

        // Register the action handler
        Router::macro('action', function (string $prefix, string $name, string $actionClass) {
            return Route::group(['prefix' => $prefix], static function (Router $router) use ($actionClass, $name) {
                $router->get('/', '\\' . ltrim($actionClass, '\\') . '@get')->name(sprintf('%s.create', $name));
                $router->post('/', '\\' . ltrim($actionClass, '\\') . '@post')->name(sprintf('%s.store', $name));
            });
        });

        $this->app->bind(SessionGuard::class, function () {
            return $this->app['auth']->guard('user');
        });

        $this->app->bind(User::class, function () {
            return $this->app['auth']->guard('user')->user();
        });

        $this->app->bind(BotMan::class, function () {
            return $this->app->make('botman');
        });
    }

    public function boot()
    {
        $blade = Container::getInstance()->make(BladeCompiler::class);
        $blade->directive('markdown', static function (string $content) {
            return "<?php echo (new \League\CommonMark\CommonMarkConverter)->convertToHtml({$content}); ?>";
        });
    }
}
