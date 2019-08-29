<?php

namespace Ollieread\Core;

use BotMan\BotMan\BotMan;
use Illuminate\Auth\SessionGuard;
use Illuminate\Container\Container;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Ollieread\Core\Actions\Feeds;
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

        $this->app->bind(SessionGuard::class, function () {
            return $this->app['auth']->guard('user');
        });

        $this->app->bind(User::class, function () {
            return $this->app['auth']->guard('user')->user();
        });

        $this->app->when(Feeds::class)
            ->needs(FilesystemManager::class)
            ->give(function() {
                return $this->app->make(FilesystemManager::class);
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
