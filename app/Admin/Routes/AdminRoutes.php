<?php

namespace Ollieread\Admin\Routes;

use Illuminate\Routing\Router;
use Ollieread\Admin\Actions as Actions;
use Ollieread\Core\Support\Contracts\Routes;

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
        $router->get('/article/{article}/edit')->name('article.edit')->uses(Actions\Articles\Edit::class . '@edit');
        $router->post('/article/{article}/edit')->name('article.update')->uses(Actions\Articles\Edit::class . '@update');
    }

    private function topicRoutes(Router $router): void
    {
        $router->get('/topics')->name('topic.index')->uses(Actions\Topics\Index::class);
        $router->get('/topic/create')->name('topic.create')->uses(Actions\Topics\Create::class . '@create');
        $router->post('/topic/create')->name('topic.store')->uses(Actions\Topics\Create::class . '@store');
        $router->get('/topic/{topic}/edit')->name('topic.edit')->uses(Actions\Topics\Edit::class . '@edit');
        $router->post('/topic/{topic}/edit')->name('topic.update')->uses(Actions\Topics\Edit::class . '@update');
        $router->get('/topic/{topic}/delete')->name('topic.delete')->uses(Actions\Topics\Delete::class . '@delete');
        $router->post('/topic/{topic}/delete')->name('topic.destroy')->uses(Actions\Topics\Delete::class . '@destroy');
    }

    private function userRoutes(Router $router): void
    {
        $router->get('/users')->name('user.index')->uses(Actions\Users\Index::class);
        $router->get('/user/{user}/edit')->name('user.edit')->uses(Actions\Users\Edit::class . '@edit');
        $router->post('/user/{user}/edit')->name('user.update')->uses(Actions\Users\Edit::class . '@update');
        $router->get('/user/{user}/reset')->name('user.reset')->uses(Actions\Users\Reset::class);
        $router->get('/user/{user}/toggle')->name('user.toggle')->uses(Actions\Users\Toggle::class);
        $router->get('/user/{user}/verify')->name('user.verify')->uses(Actions\Users\Verify::class);
        $router->get('/user/{user}/resend')->name('user.resend')->uses(Actions\Users\Resend::class);
        $router->get('/user/{user}/delete')->name('user.delete')->uses(Actions\Users\Delete::class . '@delete');
        $router->post('/user/{user}/delete')->name('user.destroy')->uses(Actions\Users\Delete::class . '@destroy');
    }

    private function versionRoutes(Router $router): void
    {
        $router->get('/versions')->name('version.index')->uses(Actions\Versions\Index::class);
        $router->get('/version/create')->name('version.create')->uses(Actions\Versions\Create::class . '@create');
        $router->post('/version/create')->name('version.store')->uses(Actions\Versions\Create::class . '@store');
        $router->get('/version/{version}/edit')->name('version.edit')->uses(Actions\Versions\Edit::class . '@edit');
        $router->post('/version/{version}/edit')->name('version.update')->uses(Actions\Versions\Edit::class . '@update');
        $router->get('/version/{version}/delete')->name('version.delete')->uses(Actions\Versions\Delete::class . '@delete');
        $router->post('/version/{version}/delete')->name('version.destroy')->uses(Actions\Versions\Delete::class . '@destroy');
    }
}
