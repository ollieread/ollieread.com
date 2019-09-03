<?php

namespace Ollieread\Users\Transformers;

use League\Fractal\TransformerAbstract;
use Ollieread\Users\Models\User;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user): array
    {
        return [
            'id'       => $user->id,
            'avatar'   => $user->avatar ?? $user->gravatar,
            'username' => $user->username,
        ];
    }
}
