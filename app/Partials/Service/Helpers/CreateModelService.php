<?php

namespace App\Partials\Service\Helpers;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateModelService extends CoreHelperService
{
    public function create(
        mixed $data,
        ?Closure $succed = null,
        ?Closure $failed = null,
        ?Closure $executed = null,
        ?Closure $mounted = null
    ){
        try {
            DB::connection($this->model->getConnectionName())->beginTransaction();
            $this->mounted($mounted, $data);
            $data = $this->checkColumn($data, 'created_by');
            $translations = [];
            if (isset($data['translations'])) {
                $translations = $data['translations'];
                unset($data['translations']);
            }
            $data = $this->serialize($data);
            $model = $this->model->create($data);
            $this->executed($executed, $model, $data);
            $this->createTranslations($translations, $model);
            $model->refresh();
            $this->succed($succed, $model, $data);
            DB::connection($this->model->getConnectionName())->commit();
            return $model;
        } catch (\Throwable $th) {
            DB::connection($this->model->getConnectionName())->rollBack();
            Log::error($th->getMessage() . " " . static::class . '::' . __FUNCTION__ . ' ' . $th->getLine() ?? __LINE__ . ' line');
            $this->failed($failed, $th, $data);
            return $th;
        }
    }

    protected function serialize($data)
    {
        foreach ($this->coreService->serializingToJsonFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode($data[$field]);
            }
        }
        return $data;
    }

    protected function createTranslations($translations, $model)
    {
        if ($this->translation()) {
            foreach ($translations as $translation) {
                $this->translation()::create($translation + ['object_id' => $model->id]);
            }
        }
    }

    public function translation()
    {
        return $this->model->translationClass ?? null;
    }

    private function checkColumn($data, $column)
    {
        $isColExist = Schema::connection($this->model->getConnectionName())->hasColumn($this->model->getTable(), $column);
        if ($isColExist)
            $data[$column] = auth()->id() ?? 1;

        return $data;
    }
}
