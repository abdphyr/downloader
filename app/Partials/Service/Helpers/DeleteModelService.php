<?php

namespace App\Partials\Service\Helpers;

use Closure;
use Illuminate\Support\Facades\DB;

class DeleteModelService extends CoreHelperService
{
    public function delete(
        mixed $id,
        ?Closure $succed = null,
        ?Closure $failed = null,
        ?Closure $executed = null,
        ?Closure $mounted = null
    ){
        try {
            DB::connection($this->model->getConnectionName())->beginTransaction();
            $this->mounted($mounted, $id);
            $model = $this->findById($id);
            if ($model) {
                $this->executed($executed, $model);
                if ($this->translation()) $model->translations()->delete();
                $model->delete();
                $this->succed($succed, $model, $id);
                DB::connection($this->model->getConnectionName())->commit();
                return $model;
            } else {
                throw new \Exception($this->coreService->getModelName() . '_not_found', 404);
            }
        } catch (\Throwable $th) {
            DB::connection($this->model->getConnectionName())->rollBack();
            $this->failed($failed, $th, $id);
            return $th;
        }
    }

    public function softDelete(
        mixed $id,
        ?Closure $succed = null,
        ?Closure $failed = null,
        ?Closure $executed = null,
        ?Closure $mounted = null
    ){
        try {
            DB::connection($this->model->getConnectionName())->beginTransaction();
            $this->mounted($mounted, $id);
            $model = $this->findById($id);
            if ($model) {
                $this->executed($executed, $model, $id);
                if ($this->translation()) $model->translations()->delete();
                $model->deleted_by = auth()->id();
                $model->save();
                $model->delete();
                $this->succed($succed, $model, $id);
                DB::connection($this->model->getConnectionName())->commit();
                return $model;
            } else {
                throw new \Exception($this->coreService->getModelName() . '_not_found', 404);
            }
        } catch (\Throwable $th) {
            DB::connection($this->model->getConnectionName())->rollBack();
            $this->failed($failed, $th, $id);
            return $th;
        }
    }

    public function translation()
    {
        return $this->model->translationClass ?? null;
    }
}
