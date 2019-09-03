<?php

namespace Ollieread\Users\Routes;

use Illuminate\Routing\Router;
use Ollieread\Core\Support\Contracts\Routes;
use Ollieread\Users\Actions\Admin as Actions;

class AdminRoutes implements Routes
{
    public function __invoke(Router $router)
    {
        $router->middleware(['auth', 'can:ADMIN_MASTER'])->group(function (Router $router) {
            $router->get('/')->name('dashboard')->uses(Actions\Dashboard::class);
            $this->userRoutes($router);
            $this->topicRoutes($router);
            $this->versionRoutes($router);
            $this->articleRoutes($router);
        });
    }

    public function name(): ?string
    {
        return 'admin:';
    }

    public function prefix(): ?string
    {
        return '/admin';
    }

    private function articleRoutes(Router $router): void
    {
        $router->get('/articles')->name('article.index')->uses(Actions\Articles\Index::class);
    }

    private function topicRoutes(Router $router): void
    {
        $router->get('/topics')->name('topic.index')->uses(Actions\Topics\Index::class);
    }

    private function userRoutes(Router $router): void
    {
        $router->get('/users')->name('user.index')->uses(Actions\Users\Index::class);
        $router->get('/user/{user}/edit')->name('user.edit')->uses(Actions\Users\Edit::class . '@edit');
        $router->post('/user/{user}/edit')->name('user.update')->uses(Actions\Users\Edit::class . '@update');
        $router->get('/user/{user}/reset')->name('user.reset')->uses(Actions\Users\Reset::class);
        $router->get('/user/{user}/toggle')->name('user.toggle')->uses(Actions\Users\Toggle::class);
        $router->get('/user/{user}/verify')->name('user.verify')->uses(Actions\Users\Verify::class);
    }

    private function versionRoutes(Router $router): void
    {
        $router->get('/versions')->name('version.index')->uses(Actions\Versions\Index::class);
    }
}
