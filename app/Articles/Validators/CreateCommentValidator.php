<?php

namespace Ollieread\Articles\Validators;

use Ollieread\Core\Support\Validator;

class CreateCommentValidator extends Validator
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'comment' => ['required', 'min:10'],
        ];
    }
}