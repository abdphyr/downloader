<?php

namespace App\Partials\Service;

use App\Partials\Service\Traits\HelperResolver;
use Closure;
use Throwable;

abstract class BaseService extends CoreService
{
    use HelperResolver;
    
    public function __construct()
    {
        if (isset($this->{'defaultAuthorizeMethods'})) {
            $this->authorizeMethods = array_merge($this->authorizeMethods, $this->{'defaultAuthorizeMethods'});
        }
        $this->translation = $this->model->translationClass ?? null;
        if (!empty($this->translation)) {
            $this->relations += ['translations', 'translation'];
        }
    }

    public function readPaginated($data = [], $limit = 20, $page = 1)
    {
        $this->authorizeMethod(__FUNCTION__);
        $list = $this->readModelService()->getList($data, $limit, $page);
        return $this->makeResponse(data: $this->withResource($list, true));
    }

    public function readAll($data = [])
    {
        $all =  $this->readModelService()->getAll($data);
        return $this->makeResponse(data: $this->withResource($all, true));
    }

    public function getCount($data = [])
    {
        return $this->readModelService()->getCount($data);
    }

    public function create($data)
    {
        $this->createModelService()->create(
            data: $data,
            executed: function ($model) {
                $this->authorizeMethod('createApi');
            }, 
            succed: function ($model) {
                $this->setOkReturnedData(data: $this->withResource($model), code: 201);
            }, 
            failed: function (Throwable $th) {
                $this->setNotOkReturnedData($th);
            }
        );
        return $this->return();
    }   

    public function update($id, $data)
    {
        $this->updateModelService()->edit(
            id: $id, 
            data: $data,
            executed: function ($model) {
                $this->authorizeMethod('editApi', $model);
            },
            succed: function ($model) {
                $this->setOkReturnedData(data: $this->withResource($model));
            },
            failed: function (Throwable $th) {
                $this->setNotOkReturnedData($th);
            }
        );
        return $this->return();
    }

    public function read($id)
    {
        $this->readModelService()->show(
            id: $id, 
            executed: function ($model) {
                $this->authorizeMethod('showApi', $model);
            },
            succed: function ($model) {
                $this->setOkReturnedData(data: $this->withResource($model));
            },
            failed: function (Throwable $th) {
                $this->setNotOkReturnedData($th);
            }
        );
        return $this->return();
    }

    public function delete($id)
    {
        $this->deleteModelService()->delete(
            id: $id, 
            executed: function ($model) {
                $this->authorizeMethod('deleteApi', $model);
            },
            succed: function ($model) {
                $this->setOkReturnedData(code: 204);
            },
            failed: function (Throwable $th) {
                $this->setNotOkReturnedData($th);
            }
        );
        return $this->return();
    }

    public function softDelete($id)
    {
        $this->deleteModelService()->softDelete(
            id: $id, 
            executed: function ($model) {
                $this->authorizeMethod('softDeleteApi', $model);
            },
            succed: function ($model) {
                $this->setOkReturnedData(code: 204);
            },
            failed: function (Throwable $th) {
                $this->setNotOkReturnedData($th);
            }
        );
        return $this->return();
    }


    public function getPaginated($data = [], $limit = 20, $page = 1)
    {
        $this->authorizeMethod(__FUNCTION__);
        return $this->readModelService()->getList($data, $limit, $page);
    }

    public function getAll($data = [])
    {
        return $this->readModelService()->getAll($data);
    }

    public function store(
        mixed $data,
        ?Closure $succed = null,
        ?Closure $failed = null,
        ?Closure $executed = null,
        ?Closure $mounted = null
    ){
        return $this->createModelService()->create(
            data: $data,
            succed: $succed,
            failed: $failed,
            executed: $executed,
            mounted: $mounted
        );
    }

    public function edit(
        mixed $id,
        mixed $data,
        ?Closure $succed = null,
        ?Closure $failed = null,
        ?Closure $executed = null,
        ?Closure $mounted = null
    ){
        return $this->updateModelService()->edit(
            id: $id,
            data: $data,
            succed: $succed,
            failed: $failed,
            executed: $executed,
            mounted: $mounted
        );
    }

    public function show(
        mixed $id,
        ?Closure $succed = null,
        ?Closure $failed = null,
        ?Closure $executed = null,
        ?Closure $mounted = null
    ){
        return $this->readModelService()->show(
            id: $id,
            succed: $succed,
            failed: $failed,
            executed: $executed,
            mounted: $mounted
        );
    }

    public function destroy(
        mixed $id,
        ?Closure $succed = null,
        ?Closure $failed = null,
        ?Closure $executed = null,
        ?Closure $mounted = null
    ){
        return $this->deleteModelService()->delete(
            id: $id,
            succed: $succed,
            failed: $failed,
            executed: $executed,
            mounted: $mounted
        );
    }

    public function softDestroy(
        mixed $id,
        ?Closure $succed = null,
        ?Closure $failed = null,
        ?Closure $executed = null,
        ?Closure $mounted = null
    ){
        return $this->deleteModelService()->softDelete(
            id: $id,
            succed: $succed,
            failed: $failed,
            executed: $executed,
            mounted: $mounted
        );
    }
}
