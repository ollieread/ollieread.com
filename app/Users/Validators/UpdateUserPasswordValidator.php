<?php

namespace Ollieread\Users\Validators;

use Ollieread\Core\Support\Rules;
use Ollieread\Core\Support\Validator;

class UpdateUserPasswordValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'password'         => ['required', 'min:8', 'confirmed'],
            'current_password' => ['sometimes', Rules::password()],
        ];
    }
}
