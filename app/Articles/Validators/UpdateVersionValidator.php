<?php

namespace Ollieread\Articles\Validators;

use Illuminate\Validation\Rule;
use Ollieread\Core\Support\Validator;

class UpdateVersionValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'         => ['required'],
            'slug'         => ['required', Rule::unique('versions')->ignoreModel($this->model)],
            'description'  => ['required'],
            'docs'         => [],
            'release_date' => ['required', 'date'],
        ];
    }
}
