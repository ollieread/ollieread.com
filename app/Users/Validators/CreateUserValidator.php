<?php

namespace Ollieread\Users\Validators;

use Illuminate\Validation\Rule;
use Ollieread\Core\Support\Validator;

class CreateUserValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => ['required', Rule::unique('users')],
            'email'    => ['required', 'email', Rule::unique('users')],
            'password' => ['sometimes', 'required', 'min:8', 'confirmed'],
        ];
    }
}