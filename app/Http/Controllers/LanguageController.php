<?php

namespace App\Http\Controllers;

use App\Services\LanguageService;
use App\Http\Requests\IndexRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageUpsertRequest;

class LanguageController extends Controller
{

    public function __construct(protected LanguageService $service)
    {
    }

    public function all(IndexRequest $indexRequest)
    {
        return $this->service->getAll($indexRequest);
    }

    public function index(IndexRequest $indexRequest)
    {
        return $this->service->readPaginated($indexRequest);
    }

    public function store(LanguageUpsertRequest $upsertRequest)
    {
        return $this->service->create($upsertRequest->validated());
    }

    public function show($id)
    {
        return $this->service->read($id);
    }

    public function update($id, LanguageUpsertRequest $upsertRequest)
    {
        return $this->service->update($id, $upsertRequest->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
