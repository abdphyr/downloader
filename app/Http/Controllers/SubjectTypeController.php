<?php

namespace App\Http\Controllers;

use App\Services\SubjectTypeService;
use App\Http\Requests\IndexRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectTypeUpsertRequest;

class SubjectTypeController extends Controller
{

    public function __construct(protected SubjectTypeService $service)
    {
        //
    }

    public function index(IndexRequest $indexRequest)
    {
        return $this->service->readPaginated($indexRequest);
    }

    public function store(SubjectTypeUpsertRequest $upsertRequest)
    {
        return $this->service->create($upsertRequest->validated());
    }

    public function show($id)
    {
        return $this->service->read($id);
    }

    public function update($id, SubjectTypeUpsertRequest $upsertRequest)
    {
        return $this->service->update($id, $upsertRequest->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
