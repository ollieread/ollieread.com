<?php

namespace Ollieread\Core;

use Illuminate\Auth\SessionGuard;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Factory;
use Ollieread\Core\Actions\Feeds;
use Ollieread\Core\Composers\HoneypotComposer;
use Ollieread\Core\Routes\CoreRoutes;
use Ollieread\Core\Support\Routes;
use Ollieread\Users\Models\User;

class CoreServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $blade = $this->app->make(BladeCompiler::class);

        $blade->directive('markdown', static function (string $content) {
            return "<?php echo (new \League\CommonMark\CommonMarkConverter)->convertToHtml({$content}); ?>";
        });

        $view = $this->app->make(Factory::class);
        $view->composer('partials.honeypot', HoneypotComposer::class);
    }

    public function register(): void
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
            ->give(function () {
                return $this->app->make(FilesystemManager::class);
            });
    }
}
