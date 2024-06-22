<?php

namespace App\Http\Requests;

class UserUpsertRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $required = ($this->isMethod('put')) ? 'nullable' : 'required';
        return [
            'username' => [$required, 'string'],
            'firstname' => ['nullable', 'string'],
            'lastname' => ['nullable', 'string'],
            'file' => ['nullable'],
            'files' => ['nullable', 'array']
        ];
    }
}
