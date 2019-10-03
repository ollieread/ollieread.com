<?php

namespace Ollieread\Articles\Validators;

use Illuminate\Validation\Rule;
use Ollieread\Core\Support\Validator;

class UpdateTopicValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'        => ['required'],
            'slug'        => ['required', Rule::unique('topics')->ignoreModel($this->model)],
            'description' => ['required'],
            'content'     => [],
        ];
    }
}
