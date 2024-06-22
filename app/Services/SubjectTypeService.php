<?php

namespace App\Services;

use App\Models\SubjectType;
use App\Services\BaseService;
use App\Http\Resources\SubjectTypeResource;

class SubjectTypeService extends BaseService
{
    public function __construct(SubjectType $serviceModel)
    {
        $this->model = $serviceModel;
        $this->resource = SubjectTypeResource::class;

        $this->likableFields = [
        ];

        $this->equalableFields = [
        ];

        $this->dateIntervalFields = [
            'created_at',
            'updated_at'
        ];
        $this->translationFields = [
            'name'
        ];
        parent::__construct();
    }
}
