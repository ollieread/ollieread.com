<?php

namespace Ollieread\Users\Validators;

use Ollieread\Core\Support\Validator;

class LoginValidator extends Validator
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ];
    }
}