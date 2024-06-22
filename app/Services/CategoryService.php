<?php

namespace App\Services;

use App\Models\Category;
use App\Services\BaseService;
use App\Http\Resources\CategoryResource;

class CategoryService extends BaseService
{
    public function __construct(Category $serviceModel)
    {
        $this->model = $serviceModel;
        $this->resource = CategoryResource::class;

        $this->likableFields = [
        ];

        $this->equalableFields = [
            'id',
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

    public function specialFilter()
    {
        $this->query->when(request('parents'), function ($q) {
            $q->whereNull('parent_id');
        });
    }
}
