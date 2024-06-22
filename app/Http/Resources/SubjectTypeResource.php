<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectTypeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
			'translation' => $this->translation,
			'name' => $this->translation?->name,
			'translations' => $this->translations,
        ];
    }
}
