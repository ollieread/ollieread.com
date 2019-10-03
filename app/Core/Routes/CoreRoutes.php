<?php

namespace Ollieread\Core\Routes;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Ollieread\Core\Actions;
use Ollieread\Core\Support\Contracts\Routes;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CoreRoutes implements Routes
{
    public function __invoke(Router $router)
    {
        $router->view('/', 'home')->name('home');
        $router->get('/out', static function (Request $request) {
            $url = $request->query('url');

            if ($url) {
                return redirect()->to(urldecode($url));
            }

            throw new NotFoundHttpException;
        })->name('out');

        $this->fileResourceRoutes($router);
        $router->fallback(Actions\Fallback::class)->name('fallback');
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

    private function fileResourceRoutes(Router $router): void
    {
        $router->get('/sitemap.xml', Actions\Feeds::class . '@sitemap');
        $router->get('/feed.rss', Actions\Feeds::class . '@rss');

        if (config('app.env', 'development') !== 'production') {
            $router->get('/robots.txt', static function () {
                return "User-agent: *\n'Disallow: /";
            });
        }
    }
}
