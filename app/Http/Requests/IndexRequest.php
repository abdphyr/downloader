<?php

namespace App\Http\Requests;


class IndexRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'pagination' => ['nullable'],
            'page' => ['nullable'],
            'limit' => ['nullable'],
            'filter' => ['nullable'],
            'sort' => ['nullable'],
            'language_code' => ['nullable'],
        ];
    }
}
