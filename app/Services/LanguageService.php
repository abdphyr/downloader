<?php

namespace App\Services;

use App\Http\Resources\LanguageResource;
use App\Models\Language;
use App\Services\BaseService;

class LanguageService extends BaseService
{
    public function __construct(Language $language)
    {
        $this->model = $language;
        $this->resource = LanguageResource::class;
        $this->resources = [LanguageResource::class];

        $this->likableFields = [
            'name',
            'code',
        ];

        $this->equalableFields = [
            'id',
            'is_active',
            'created_by',
            'updated_by',
        ];

        parent::__construct();
    }
}
