<?php

namespace Ollieread\Users\Actions;

use Exception;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\Factory;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\CreateUser;
use Ollieread\Users\Operations\GetUser;
use Ollieread\Users\Operations\GetUserSocial;
use Ollieread\Users\Operations\SaveUserSocial;

class Social extends Action
{
    /**
     * @var \Laravel\Socialite\SocialiteManager|\Laravel\Socialite\Contracts\Factory
     */
    private $socialite;

    /**
     * @var \Illuminate\Auth\SessionGuard
     */
    private $auth;

    public function __construct(Factory $socialite, SessionGuard $auth)
    {
        $this->socialite = $socialite;
        $this->auth      = $auth;
    }

    public function create(string $provider, Request $request)
    {
        $this->handleRedirectAfter($request);

        return $this->socialite->driver($provider)->redirect();
    }

    public function store(string $provider, Request $request): RedirectResponse
    {
        try {
            $socialUser = $this->socialite->driver($provider)->user();
        } catch (Exception $exception) {
            Alerts::error(trans('errors.unexpected'));
            return $this->response()->redirectToRoute('users:register.create');
        }

        if ($socialUser) {
            // Get the user for the social account
            $user = (new GetUser)
                ->setProvider($provider)
                ->setSocialId($socialUser->getId())
                ->perform();

            if (! $user) {
                // If there isn't one we want to get either the currently identified user or a
                // user with a matching email address
                $user = $this->auth->user() ?? (new GetUser)
                        ->setEmail($socialUser->getEmail())
                        ->perform();

                if ($user) {
                    // Now that we've found a user we want to 'save' their social profile though this
                    // would actually be a create if we've hit this
                    if (! (new SaveUserSocial)->setUser($user)->setProvider($provider)->setSocialUser($socialUser)->perform()) {
                        // If we failed we want to error
                        Alerts::error(trans('error.unexpected'));
                        return $this->response()->redirectToRoute('users:sign-in.create');
                    }
                } else {
                    // Now we want to go about creating a new user
                    $user = (new CreateUser)
                        ->setInput([
                            'username' => $socialUser->getNickname() ?? str_replace(' ', '_', strtolower($socialUser->getName())),
                            'email'    => $socialUser->getEmail(),
                            'avatar'   => $socialUser->getAvatar(),
                            'active'   => true,
                            'verified' => true,
                        ])
                        ->setProvider($provider)
                        ->setSocialUser($socialUser)
                        ->perform();
                }
            } else {
                // Since we found a user to start with we're going to want to get the associated social profile
                $social = (new GetUserSocial)->setUser($user)->setProvider($provider)->perform();

                // Now we want to update that social profile with the updated information, avatar etc
                if (! $social || ! (new SaveUserSocial)->setSocial($social)->setUser($user)->setProvider($provider)->setSocialUser($socialUser)->perform()) {
                    Alerts::error(trans('error.unexpected'));
                    return $this->response()->redirectToRoute('users:sign-in.create');
                }
            }

            if ($user) {
                $this->auth->login($user);
                return $this->response()->redirectToIntended($this->url()->route('site:home'));
            }
        }

        Alerts::error(trans('errors.unexpected'));
        return $this->response()->redirectToRoute('users:sign-in.create');
    }
}

