<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Http\Requests\IndexRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryUpsertRequest;

class CategoryController extends Controller
{

    public function __construct(protected CategoryService $service)
    {
    }

    public function all(IndexRequest $indexRequest)
    {
        return $this->service->getAll($indexRequest);
    }

    public function index(IndexRequest $indexRequest)
    {
        return $this->service->with(['children' => ['translation' => [], 'children' => ['translation' => []]]])->readPaginated($indexRequest);
    }

    public function store(CategoryUpsertRequest $upsertRequest)
    {
        return $this->service->create($upsertRequest->validated());
    }

    public function show($id)
    {
        return $this->service->with(['children' => ['translation' => [], 'children' => ['translation' => []]]])->read($id);
    }

    public function update($id, CategoryUpsertRequest $upsertRequest)
    {
        return $this->service->update($id, $upsertRequest->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
