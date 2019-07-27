<?php

namespace Ollieread\Core\Routes;

use Illuminate\Routing\Router;
use Ollieread\Core\Actions;
use Ollieread\Core\Support\Contracts\Routes;
use Ollieread\Users\Mail\Welcome;
use Ollieread\Users\Models\User;

class CoreRoutes implements Routes
{
    public function __invoke(Router $router)
    {
        $router->view('/', 'home')->name('home');
        $router->fallback(Actions\Fallback::class)->name('fallback');
        $router->get('/mail', static function () {
            $user = User::find(1);
            return new Welcome($user);
        });
        $router->view('multitenancy', 'multitenancy');
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