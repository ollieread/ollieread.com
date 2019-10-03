<?php

namespace Ollieread\Articles\Validators;

use Illuminate\Validation\Rule;
use Ollieread\Core\Support\Validator;

class CreateVersionValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'         => ['required'],
            'slug'         => ['required', Rule::unique('versions')],
            'description'  => ['required'],
            'docs'         => [],
            'release_date' => ['required', 'date'],
        ];
    }
}
