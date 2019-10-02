<?php

namespace Ollieread\Articles\Validators;

use Illuminate\Validation\Rule;
use Ollieread\Core\Support\Validator;

class CreateTopicValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'        => ['required'],
            'slug'        => ['required', Rule::unique('topics')],
            'description' => ['required'],
            'content'     => [],
        ];
    }
}
