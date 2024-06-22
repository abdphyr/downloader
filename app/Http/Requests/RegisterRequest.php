<?php

namespace App\Http\Requests;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'firstname' => ['nullable', 'string'],
            'lastname' => ['nullable', 'string'],
        ];
    }
}
