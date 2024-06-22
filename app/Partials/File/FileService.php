<?php

namespace App\Partials\File;

use App\Partials\Service\BaseService;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Illuminate\Support\Str;

class FileService extends BaseService
{
    protected BaseService $fileableService;

    public function __construct(File $serviceModel)
    {
        $this->model = $serviceModel;
    }

    public $types = [
        'image' => [
            'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
            'mime_types' => ['image/jpeg', 'image/png', 'image/gif'],
        ],
        'video' => [
            'extensions' => ['mp4', 'mov', 'avi', 'wmv', 'flv', 'mpg', 'mpeg'],
            'mime_types' => ['video/mp4', 'video/quicktime', 'video/x-ms-wmv', 'video/x-flv', 'video/mpeg', 'video/x-msvideo'],
        ],
        'audio' => [
            'extensions' => ['mp3', 'wav', 'ogg', 'aac', 'flac', 'wma', 'm4a'],
            'mime_types' => ['audio/mpeg', 'audio/x-wav', 'audio/ogg', 'audio/x-aac', 'audio/flac', 'audio/x-ms-wma'],
        ],
        'file' => [
            'extensions' => ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'txt', 'zip', 'rar', 'gpg'],
            'mime_types' => ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/pdf', 'text/plain', 'application/zip', 'application/x-rar-compressed'],
        ],
    ];

    /**
     * @param WithFile $fileableObject
     * @param array $data
     */
    public function createFile($fileableObject, $data)
    {
        try {
            $fileTypes = $fileableObject->fileTypes();
            $result = [];
            foreach ($fileTypes as $type => $method) {
                if (request()->hasFile($method)) {
                    $file = request()->file($method);
                    if (!is_array($file)) {
                        $file = Arr::wrap($file);
                    }
                    foreach ($file as $value) {
                        $info = $this->uploadFile($fileableObject, $value, $method);
                        $fileObject = $this->storeFileModel($fileableObject, $info, $type);
                        $result[] = $fileObject;
                    }
                }
            }
            $this->onSucced($result);
            $this->setOkReturnedData(data: $result);
        } catch (Throwable $th) {
            $this->onFailed($th);
            $this->setNotOkReturnedData($th);
        }
        return $this->return();
    }

    /**
     * @param WithFile $fileableObject
     * @param \Illuminate\Http\UploadedFile $file
     * @param array $data
     */
    public function updateFile($fileableObject, $file, $data)
    {
        try {
            if ($fileObject = $fileableObject->files()->find($file)) {
                $this->onExecuted($fileObject);
                if (!$this->deleteFileFromStore($fileObject)) {
                    throw new Exception('cant_delete_file', 500);
                }
                $fileTypes = $fileableObject->fileTypes();
                $method = $fileTypes[$fileObject->type ?? File::FILE];
                $file = request()->file($method);
                $info = $this->uploadFile($fileableObject, $file, $method);
                $fileObject->update([
                    'path' => $info['storpath'],
                    'info' => json_encode($info)
                ]);
                $this->onSucced($fileObject, $data);
                $this->setOkReturnedData(data: $fileObject);
            } else throw new Exception('file_not_found', 404);
        } catch (\Throwable $th) {
            $this->onFailed($th, $data);
            $this->setNotOkReturnedData($th, $data);
        }
        return $this->return();
    }

    /**
     * @param WithFile $fileableObject
     * @param int $file
     */
    public function deleteFile($fileableObject, $file)
    {
        try {
            if ($fileObject = $fileableObject->files()->find($file)) {
                $this->onExecuted($fileObject);
                if (!$this->deleteFileFromStore($fileObject)) {
                    throw new Exception('cant_delete_file', 500);
                }
                $fileObject->delete();
                $this->onSucced($fileObject);
                $this->setResponse(code: 204);
            } else throw new Exception('file_not_found', 404);
        } catch (\Throwable $th) {
            $this->onFailed($th);
            $this->setNotOkReturnedData($th);
        }
        return $this->return();
    }

    /**
     * @param WithFile $fileableObject
     * @param int $file
     */
    public function downloadFile($fileableObject, $file)
    {
        try {
            if ($fileObject = $fileableObject->files()->find($file)) {
                $this->onExecuted($fileObject);
                if (Storage::exists($fileObject->path)) {
                    $streamedResponse = Storage::download($fileObject->path);
                    $this->onSucced($streamedResponse);
                    $this->setOkReturnedData($streamedResponse);
                } else throw new Exception('file_not_found', 404);
            } else throw new Exception('file_not_found', 404);
        } catch (\Throwable $th) {
            $this->onFailed($th);
            $this->setNotOkReturnedData($th);
        }
        return $this->return();
    }

    public function deleteFileFromStore($fileObject)
    {
        if (Storage::exists($fileObject->path)) {
            return Storage::delete($fileObject->path);
        } else return true;
    }

    /**
     * @param WithFile $fileableObject
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $method
     */
    protected function uploadFile($fileableObject, $fileObj, $method)
    {
        $settings = $fileableObject->fileSettings();
        $scope = getProp($settings, 'scope', 'files');
        $folder = getProp($settings, 'folder', $this->getModelName($fileableObject));
        $filename = (string)Str::uuid();
        $extension = $fileObj->getClientOriginalExtension();
        $fileType = $this->getTypeByExtension($extension);
        $info = [];
        $path = $scope . '/' . $folder . (in_array($method, ['file', 'files']) ? '' : ('/' . $method));
        $name = $filename . '.' . $extension;
        Storage::putFileAs($path, $fileObj, $name);
        $info = array_merge($info, [
            'storpath' => $path . '/' . $name,
            'path' => $path,
            'filename' => $filename,
            'extension' => $extension,
            'folder' => $folder,
            'type' => $fileType,
            'size' => $fileObj->getSize(),
            'originalName' => pathinfo($fileObj->getClientOriginalName(), PATHINFO_FILENAME),
        ]);
        return $info;
    }

    protected function getFiles($data)
    {
        $files = [];
        if (request()->hasFile('files') || request('files')) {
            $files = $data['files'];
        }
        if (request()->hasFile('file') || request('file')) {
            $files[] = $data['file'];
        }
        return $files;
    }

    protected function getTypeByExtension($ext)
    {
        foreach ($this->types as $type => $value) {
            if (in_array($ext, $value['extensions'])) return $type;
        }
        return 'other';
    }

    /**
     * @param WithFile $fileableObject
     * @param string $info 
     * @param int $type
     */
    protected function storeFileModel($fileableObject, $info, $type)
    {
        $fileTypes = $fileableObject->fileTypes();
        $builder = $fileableObject->{$fileTypes[$type ?? File::FILE]}();
        $fileObject = $builder->create([
            'path' => $info['storpath'],
            'info' => json_encode($info),
            'type' => $type ?? File::FILE
        ]);
        return $fileObject;
    }
}
