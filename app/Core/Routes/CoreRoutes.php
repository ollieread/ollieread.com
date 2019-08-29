<?php

namespace Ollieread\Core\Routes;

use Illuminate\Routing\Router;
use Ollieread\Core\Actions;
use Ollieread\Core\Support\Contracts\Routes;

class CoreRoutes implements Routes
{
    public function __invoke(Router $router)
    {
        $router->get('/sitemap.xml', Actions\Feeds::class . '@sitemap');
        $router->get('/feed.rss', Actions\Feeds::class . '@rss');
        $router->view('/', 'home')->name('home');
        $router->fallback(Actions\Fallback::class)->name('fallback');
        /*$router->get('/mail', static function () {
            $user = User::find(1);
            return new Welcome($user);
        });*/
        $router->view('multitenancy', 'multitenancy.index');
    }

    public function name(): ?string
    {
        return 'site:';
    }

    public function prefix(): ?string
    {
        return null;
    }
}
