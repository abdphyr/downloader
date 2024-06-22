<?php

namespace App\Http\Requests;

class LanguageUpsertRequest extends BaseRequest
{
    public function rules()
    {
        $required = ($this->isMethod('put')) ? 'nullable' : 'required';
        
        return [
            'name' => [$required, 'string'],
            'code' => [$required, 'string']
        ];
    }
}
