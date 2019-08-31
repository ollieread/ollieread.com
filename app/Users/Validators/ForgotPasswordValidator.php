<?php

namespace Ollieread\Users\Validators;

use Ollieread\Core\Support\Validator;

class ForgotPasswordValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required'],
        ];
    }
}
