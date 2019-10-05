<?php

namespace Ollieread\Core\Validators;

use Illuminate\Validation\Rule;
use Ollieread\Core\Models\Redirect;
use Ollieread\Core\Support\Validator;

class UpdateRedirectValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'from' => ['required', Rule::unique((new Redirect)->getTable(), 'from')->ignoreModel($this->model)],
            'to'   => ['required'],
        ];
    }
}
