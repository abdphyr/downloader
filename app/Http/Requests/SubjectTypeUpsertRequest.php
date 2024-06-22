<?php

namespace App\Http\Requests;

class SubjectTypeUpsertRequest extends BaseRequest
{
    public function rules()
    {
        $required = ($this->isMethod('put')) ? 'nullable' : 'required';
        
        return [
            'translations' => [$required, 'array'],
            'translations.*' => [$required, 'array'],
            'translations.*.id' => ['nullable'],
            'translations.*.name' => [$required, 'string'],
            'translations.*.language_code' => [$required, 'string'],
        ];
    }
}
