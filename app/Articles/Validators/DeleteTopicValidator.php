<?php

namespace Ollieread\Articles\Validators;

use Ollieread\Core\Support\Rules;
use Ollieread\Core\Support\Validator;

class DeleteTopicValidator extends Validator
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
