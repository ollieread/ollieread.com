<?php

namespace Ollieread\Users\Routes;

use Illuminate\Routing\Router;
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
            $router->post('/register')->name('register.store')->uses(Actions\Register::class . '@store');

            $router->get('/sign-in')->name('sign-in.create')->uses(Actions\SignIn::class . '@create');
            $router->post('/sign-in')->name('sign-in.store')->uses(Actions\SignIn::class . '@store');

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

                // API controls
                $router->get('/api')->name('api.edit')->uses(Actions\Account\API::class . '@edit');
                $router->post('/api/view')->name('api.view')->uses(Actions\Account\API::class . '@view');
                $router->post('/api/reset')->name('api.reset')->uses(Actions\Account\API::class . '@reset');

                // Billing controls
                $router->get('/billing')->name('billing.edit')->uses(Actions\Account\Billing::class . '@edit');
                $router->post('/billing')->name('billing.update')->uses(Actions\Account\Billing::class . '@update');

                // User purchases
                $router->get('/purchases')->name('purchase.index')->uses(Actions\Account\Purchases::class . '@index');
                $router->get('/purchase/{purchase}')->name('purchase.view')->uses(Actions\Account\Purchases::class . '@view');
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
