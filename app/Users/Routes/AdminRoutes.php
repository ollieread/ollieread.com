<?php

namespace Ollieread\Users\Routes;

use Illuminate\Routing\Router;
use Ollieread\Core\Support\Contracts\Routes;
use Ollieread\Users\Actions\Admin as Actions;
use Ollieread\Users\Support\Permissions;

class AdminRoutes implements Routes
{
    public function __invoke(Router $router)
    {
        $router->middleware(['auth', 'can:ADMIN_MASTER'])->group(static function (Router $router) {
            $router->get('/')->name('dashboard')->uses(Actions\Dashboard::class);
            $router->get('/users')->name('user.index')->uses(Actions\Users\Index::class);
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
}
