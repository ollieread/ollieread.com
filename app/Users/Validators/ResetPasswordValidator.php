<?php

namespace Ollieread\Users\Validators;

use Ollieread\Core\Support\Validator;

class ResetPasswordValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'    => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }
}
