<?php

namespace Ollieread\Users\Routes;

use Illuminate\Routing\Router;
use Ollieread\Core\Middleware\CheckHoneypot;
use Ollieread\Core\Support\Contracts\Routes;
use Ollieread\Users\Actions;

class UserRoutes implements Routes
{

    public function __invoke(Router $router)
    {
        $router->get('/social/{provider}')->name('social.auth')->uses(Actions\Social::class . '@create');
        $router->get('/social/{provider}/response')->name('social.response')->uses(Actions\Social::class . '@store');

        $router->middleware('guest')->group(static function (Router $router) {
            $router->get('/register')->name('register.create')->uses(Actions\Register::class . '@create');
            $router->post('/register')->name('register.store')->uses(Actions\Register::class . '@store')->middleware(CheckHoneypot::class);

            $router->get('/sign-in')->name('sign-in.create')->uses(Actions\SignIn::class . '@create');
            $router->post('/sign-in')->name('sign-in.store')->uses(Actions\SignIn::class . '@store');

            $router->get('/forgot-password')->name('password.forgot.create')->uses(Actions\Password\Forgot::class . '@create');
            $router->post('/forgot-password')->name('password.forgot.store')->uses(Actions\Password\Forgot::class . '@store');
            $router->get('/reset-password/{token}')->name('password.reset.create')->uses(Actions\Password\Reset::class . '@create');
            $router->post('/reset-password')->name('password.reset.store')->uses(Actions\Password\Reset::class . '@store');

            $router->get('/verify/{user}')->name('verify')->uses(Actions\Verify::class)->middleware('signed');
        });

        $router->middleware('auth')->group(function (Router $router) {
            $router->get('/sign-out')->name('sign-out')->uses(Actions\SignOut::class);

            $router->name('account.')->prefix('/account')->group(static function (Router $router) {
                // Account details
                $router->get('/details')->name('details.edit')->uses(Actions\Account\Details::class . '@edit');
                $router->post('/details')->name('details.update')->uses(Actions\Account\Details::class . '@update');

                // Social accounts
                $router->get('/social')->name('social.edit')->uses(Actions\Account\Social::class . '@edit');
                $router->post('/social')->name('social.update')->uses(Actions\Account\Social::class . '@update');
                $router->get('/social/{provider}')->name('social.destroy')->uses(Actions\Account\Social::class . '@destroy');

                // Change password
                $router->get('/password')->name('password.edit')->uses(Actions\Account\Password::class . '@edit');
                $router->post('/password')->name('password.update')->uses(Actions\Account\Password::class . '@update');
            });
        });
    }

    public function name(): ?string
    {
        return 'users:';
    }

    public function prefix(): ?string
    {
        return null;
    }
}
