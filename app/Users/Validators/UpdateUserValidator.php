<?php

namespace Ollieread\Users\Validators;

use Illuminate\Validation\Rule;
use Ollieread\Core\Support\Validator;

class UpdateUserValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'     => [],
            'username' => ['required'],
            'email'    => ['required', 'email', Rule::unique('users')->ignoreModel($this->model)],
            'roles'    => ['required'],
            'roles.*'  => [Rule::exists('roles', 'id')],
        ];
    }
}
