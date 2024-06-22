<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexRequest;
use App\Http\Requests\UserUpsertRequest;
use App\Partials\File\FileActions;
use App\Partials\File\FileService;
use App\Services\UserService;

class UserController extends Controller
{
    use FileActions;
    public function __construct(
        protected UserService $userService,
        FileService $fileService
    ) {
        $this->fileableService = $userService;
        $this->fileService = $fileService;
    }

    public function index(IndexRequest $indexRequest)
    {
        return $this->userService->readPaginated($indexRequest->validated());
    }

    public function show($id)
    {
        return $this->userService->read($id);
    }

    public function store(UserUpsertRequest $userUpsertRequest)
    {
        $data = $userUpsertRequest->validated();
        $data['password'] = 'password';
        return $this->userService
            ->succed(function ($user, $data) {
                $this->fileService->createFile($user, $data);
            })
            ->create($data);
    }

    public function update($id, UserUpsertRequest $userUpsertRequest)
    {
        return $this->userService->update($id, $userUpsertRequest->validated());
    }

    public function destroy($id)
    {
        return $this->userService->delete($id);
    }
}
