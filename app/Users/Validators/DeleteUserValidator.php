<?php

namespace Ollieread\Users\Validators;

use Ollieread\Core\Support\Rules;
use Ollieread\Core\Support\Validator;

class DeleteUserValidator extends Validator
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => ['required', Rules::password()],
        ];
    }
}
