<?php

namespace Ollieread\Users\Actions;

use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\GetUser;
use Ollieread\Users\Operations\VerifyUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Verify extends Action
{
    public function __invoke(string $username)
    {
        $user = (new GetUser)
            ->setUsername($username)
            ->perform();

        if ($user) {
            $verified = (new VerifyUser)
                ->setUser($user)
                ->perform();

            if ($verified) {
                Alerts::success(trans('users.verification.success'), 'sign-in');
            } else {
                Alerts::success(trans('users.verification.failure'), 'sign-in');
            }

            return $this->response()->redirectToRoute('users:sign-in.create');
        }

        throw new NotFoundHttpException;
    }
}