<?php

namespace App\Http\Requests;

use App\Enums\SubjectStatusEnum;
use App\Enums\SubjectTypeEnum;
use App\Rules\CategoryExists;
use App\Rules\LanguageExists;
use App\Rules\SubjectTypeExists;

class SubjectUpsertRequest extends BaseRequest
{
    public function rules()
    {
        $required = ($this->isMethod('put')) ? 'nullable' : 'required';

        return [
            // translations
            'translations' => [$required, 'array'],
            'translations.*' => [$required, 'array'],
            'translations.*.id' => ['nullable'],
            'translations.*.title' => [$required, 'string'],
            'translations.*.description' => [$required, 'string'],
            'translations.*.language_code' => [$required, 'string'],

            // 
            'info' => ['nullable'],
            'status' => ['nullable', SubjectStatusEnum::in()],
            'degree' => ['nullable'],
            'type_id' => [$required, new SubjectTypeExists],
            'category_id' => [$required, new CategoryExists],
            'lang_id' => [$required, new LanguageExists],
            'author_id' => ['nullable', new CategoryExists],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,svg'],
            'document' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx'],
        ];
    }
}
