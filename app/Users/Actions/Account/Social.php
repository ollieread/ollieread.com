<?php

namespace Ollieread\Users\Actions\Account;

use Illuminate\Auth\SessionGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\DeleteUserSocial;
use Ollieread\Users\Operations\GetUserSocials;
use Ollieread\Users\Operations\UseUserSocialAvatar;

class Social extends Action
{
    /**
     * @var \Illuminate\Auth\SessionGuard
     */
    private $auth;

    public function __construct(SessionGuard $auth)
    {
        $this->auth = $auth;
    }

    public function destroy(string $provider): RedirectResponse
    {
        $user = $this->auth->user();

        if ((new DeleteUserSocial)->setUser($user)->setProvider($provider)->perform()) {
            Alerts::success(trans('users.social.delete.success', ['provider' => trans('users.provider.' . $provider)]), 'account');
            return $this->response()->redirectToRoute('users:account.social.edit');
        }

        Alerts::error(trans('errors.unexpected'), 'account');
        return $this->back();
    }

    public function edit(): Response
    {
        $user    = $this->auth->user();
        $socials = (new GetUserSocials)->setUser($user)->perform()->keyBy('provider');

        return $this->response()->view('users.account.social', compact('user', 'socials'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user      = $this->auth->user();
        $useAvatar = $request->input('use_avatar');

        if ((new UseUserSocialAvatar)->setUser($user)->setProvider($useAvatar)->perform()) {
            // Todo: Email based on password change
            Alerts::success(trans('users.social.avatar.success', ['provider' => trans('users.provider.' . $useAvatar)]), 'account');
            return $this->response()->redirectToRoute('users:account.social.edit');
        }

        Alerts::error(trans('errors.unexpected'), 'account');
        return $this->back();
    }
}
