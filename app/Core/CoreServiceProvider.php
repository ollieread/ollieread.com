<?php

namespace Ollieread\Core;

use Illuminate\Auth\SessionGuard;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Ollieread\Core\Actions\Feeds;
use Ollieread\Core\Routes\CoreRoutes;
use Ollieread\Core\Services\Honeypot;
use Ollieread\Core\Support\Routes;
use Ollieread\Users\Models\User;

class CoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $blade = $this->app->make(BladeCompiler::class);

        $blade->directive('markdown', static function (string $content) {
            return "<?php echo (new \League\CommonMark\CommonMarkConverter)->convertToHtml({$content}); ?>";
        });

        $blade->directive('honeypot', function () {
            $honeypot = $this->app->make(Honeypot::class);
            $fields   = $honeypot->getFieldNames();

            return '<div style="display:none">'
                . '<input type="text" name="' . $fields['honeypot'] . '" value="">'
                . '<input type="text" name="' . $fields['time'] . '" value="' . $honeypot->getTime() . '" autocomplete="off">'
                . '</div>';
        });
    }

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
                  ->give(function () {
                      return $this->app->make(FilesystemManager::class);
                  });
    }
}
