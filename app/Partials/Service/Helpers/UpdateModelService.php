<?php

namespace App\Partials\Service\Helpers;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateModelService extends CoreHelperService
{
    public function translation()
    {
        return $this->model->translationClass ?? null;
    }

    public function edit(
        mixed $id,
        mixed $data,
        ?Closure $succed = null,
        ?Closure $failed = null,
        ?Closure $executed = null,
        ?Closure $mounted = null
    ){
        try {
            DB::connection($this->model->getConnectionName())->beginTransaction();     
            $this->mounted($mounted, $data, $id);
            $model = $this->findById($id);
            if ($model) {
                $this->executed($executed, $model, $data, $id);
                $data['updated_by'] = auth()->id();
                $data = $this->serialize($data);
                $data = $this->updateTranslations($model, $data);
                $model->update($data);
                $model->refresh();
                $this->succed($succed, $model, $data, $id);
                DB::connection($this->model->getConnectionName())->commit();
                return $model;
            } else {
                throw new \Exception($this->coreService->getModelName() . '_not_found', 404);
            }
        } catch (\Throwable $th) {
            DB::connection($this->model->getConnectionName())->rollBack();
            Log::error($th->getMessage() . " " . static::class . '::' . __FUNCTION__ . ' ' . $th->getLine() ?? __LINE__ . ' line');              
            $this->failed($failed, $th, $data, $id);
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

    protected function updateTranslations($model, $data)
    {
        if ($this->translation()) {
            $translations = [];
            if (isset($data['translations'])) {
                $translations = $data['translations'];
                unset($data['translations']);
            }
            foreach ($translations as $translation) {
                if (isset($translation['id']) && !empty($translation['id'])) {
                    $trModel = $this->translation()::find($translation['id']);
                    if ($trModel) {
                        $trModel->update($translation);
                    }
                } else {
                    $isExists = $this->translation()::firstWhere([
                        'object_id' => $model->id,
                        'language_code' => $translation['language_code'],
                    ]);
                    if (!$isExists) {
                        $this->translation()::create($translation + ['object_id' => $model->id]);
                    }
                }
            }
        }
        return $data;
    }
}
