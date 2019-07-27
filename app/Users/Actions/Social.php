<?php

namespace Ollieread\Users\Actions;

use Exception;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\Factory;
use Ollieread\Core\Models\Topic;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\CreateUser;
use Ollieread\Users\Operations\CreateUserSocial;
use Ollieread\Users\Operations\GetUser;

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

    public function store(string $provider, Request $request)
    {
        try {
            $socialUser = $this->socialite->driver($provider)->user();
        } catch (Exception $exception) {
            Alerts::error(trans('errors.unexpected'));
            return $this->response()->redirectToRoute('users:register.create');
        }

        if ($socialUser) {
            $user = (new GetUser)
                ->setProvider($provider)
                ->setSocialId($socialUser->getId())
                ->perform();

            if (! $user) {
                $user = $this->auth->user() ?? (new GetUser)
                        ->setEmail($socialUser->getEmail())
                        ->perform();

                if ($user) {
                    if (! (new CreateUserSocial)->setUser($user)->setProvider($provider)->setSocialUser($socialUser)->perform()) {
                        Alerts::error(trans('error.unexpected'));
                        return $this->response()->redirectToRoute('users:login.create');
                    }
                } else {
                    // Now we want to go about creating a new user
                    $user = (new CreateUser)
                        ->setInput([
                            'username' => $socialUser->getNickname(),
                            'email'    => $socialUser->getEmail(),
                            'avatar'   => $socialUser->getAvatar(),
                            'active'   => true,
                            'verified' => true,
                        ])
                        ->setProvider($provider)
                        ->setSocialUser($socialUser)
                        ->perform();
                }
            }

            if ($user) {
                $this->auth->login($user);
                return $this->response()->redirectToIntended($this->url()->route('site:home'));
            }
        }

        Alerts::error(trans('errors.unexpected'));
        return $this->response()->redirectToRoute('users:login.create');
    }
}

