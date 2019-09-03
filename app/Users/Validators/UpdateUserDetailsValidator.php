<?php

namespace Ollieread\Users\Validators;

use Illuminate\Validation\Rule;
use Ollieread\Core\Support\Rules;
use Ollieread\Core\Support\Validator;

class UpdateUserDetailsValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'             => ['required'],
            'email'            => ['required', 'email', Rule::unique('users')->ignoreModel($this->model)],
            'current_password' => ['required', Rules::password()],
        ];
    }
}
