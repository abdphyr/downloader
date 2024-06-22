<?php

namespace App\Http\Controllers;

use App\Services\SubjectService;
use App\Http\Requests\IndexRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectUpsertRequest;
use App\Partials\File\FileActions;
use App\Partials\File\FileService;

class SubjectController extends Controller
{
    use FileActions;

    public function __construct(
        protected SubjectService $service,
        FileService $fileService
    ) {
        $this->fileService = $fileService;
        $this->fileableService = $service;
    }

    public function index(IndexRequest $indexRequest)
    {
        $this->service->with([
            'image',
            'document',
            'translation' => ['object_id', 'title', 'description'],
            'translations' => ['object_id', 'title', 'description'],
            'type' => [
                'id',
                'translation' => ['object_id', 'name']
            ],
            'category' => [
                'id',
                'translation' => ['object_id', 'name']
            ],
            'user',
            'author',
            'language'
        ]);
        return $this->service->readPaginated($indexRequest);
    }

    public function store(SubjectUpsertRequest $upsertRequest)
    {
        $data = $upsertRequest->validated();
        $data['code'] = '121sd';
        $data['user_id'] = auth()->user()?->id ?? 1;
        $data['author_id'] = auth()->user()?->id ?? 1;
        return $this->service
            ->succed(function ($subject) use ($data) {
                $this->fileService
                    ->failed(fn ($th) => throw $th)
                    ->createFile($subject, $data);
            })
            ->create($data);
    }

    public function show($id)
    {
        return $this->service->read($id);
    }

    public function update($id, SubjectUpsertRequest $upsertRequest)
    {
        return $this->service->update($id, $upsertRequest->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function downloadFileAction($fileable, $file)
    {
        $this->service
            ->succed(function ($subject) use ($file) {
                $this->fileService
                    ->failed(fn ($th) => $this->setNotOkResponse($th))
                    ->succed(function ($streamResponse) use ($subject) {
                        $subject->downloads++;
                        $subject->save();
                        $this->setOkResponse($streamResponse);
                    })
                    ->downloadFile($subject, $file);
            })
            ->failed(fn ($th) => $this->setNotOkResponse($th))
            ->show($fileable);
        return $this->response();
    }
}
