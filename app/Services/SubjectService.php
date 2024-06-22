<?php

namespace App\Services;

use App\Models\Subject;
use App\Services\BaseService;
use App\Http\Resources\SubjectResource;

class SubjectService extends BaseService
{
    public function __construct(Subject $serviceModel)
    {
        $this->model = $serviceModel;
        $this->resource = SubjectResource::class;

        $this->likableFields = [
            'code',
        ];

        $this->equalableFields = [
            'status',
            'degree',
            'type_id',
            'lang_id',
            'category_id',
            'user_id',
            'author_id',
        ];

        $this->dateIntervalFields = [
            'created_at',
            'updated_at'
        ];

        $this->translationFields = [
            'title',
            'description'
        ];

        parent::__construct();
    }
}
