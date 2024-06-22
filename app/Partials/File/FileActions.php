<?php

namespace App\Partials\File;

use App\Http\Requests\FileRequest;
use App\Partials\Service\BaseService;
use App\Partials\Service\Traits\ServiceResponse;

trait FileActions
{
    use ServiceResponse;
    protected FileService $fileService;
    protected BaseService $fileableService;

    public function listFileAction($fileable)
    {
        $this->fileableService
            ->succed(function($fileableObject) {
                $this->setOkResponse($fileableObject->files);
            })
            ->failed(fn($th) => $this->setNotOkResponse($th))
            ->show($fileable);
        return $this->response();
    }

    public function createFileAction($fileable, FileRequest $fileRequest)
    {
        $this->fileableService
            ->succed(function($fileableObject) use($fileRequest) {
                $this->fileService
                    ->failed(fn($th) => $this->setNotOkResponse($th))
                    ->succed(fn ($files) => $this->setOkResponse($files, 201))
                    ->createFile($fileableObject, $fileRequest->validated());
            })
            ->failed(fn($th) => $this->setNotOkResponse($th))
            ->show($fileable);
        return $this->response();
    }

    public function updateFileAction($fileable, $file, FileRequest $fileRequest)
    {
        $this->fileableService
            ->succed(function($fileableObject) use($file, $fileRequest) {
                $this->fileService
                    ->failed(fn($th) => $this->setNotOkResponse($th))
                    ->succed(fn ($fileObject) => $this->setOkResponse($fileObject))
                    ->updateFile($fileableObject, $file, $fileRequest->validated());
            })
            ->failed(fn($th) => $this->setNotOkResponse($th))
            ->show($fileable);
        return $this->response();
    }

    public function deleteFileAction($fileable, $file)
    {
        $this->fileableService
            ->succed(function($fileableObject) use($file) {
                $this->fileService
                    ->failed(fn($th) => $this->setNotOkResponse($th))
                    ->succed(fn () => $this->setOkResponse(code: 204))
                    ->deleteFile($fileableObject, $file);
            })
            ->failed(fn($th) => $this->setNotOkResponse($th))
            ->show($fileable);
        return $this->response();
    }

    public function downloadFileAction($fileable, $file)
    {
        $this->fileableService
            ->succed(function($fileableObject) use($file) {
                $this->fileService
                    ->failed(fn($th) => $this->setNotOkResponse($th))
                    ->succed(fn ($streamResponse) => $this->setOkResponse($streamResponse))
                    ->downloadFile($fileableObject, $file);
            })
            ->failed(fn($th) => $this->setNotOkResponse($th))
            ->show($fileable);
        return $this->response();
    }
}
