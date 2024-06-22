<?php

namespace App\Http\Resources;

use App\Enums\SubjectStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            
            // translations
            'title' => $this->translation?->title,
            'description' => $this->translation?->description,
			'translation' => $this->translation,
			'translations' => $this->translations,

            // info
            'code' => $this->code,
            'info' => $this->info,

            // enums
            'status' => SubjectStatusEnum::getData($this->status),
            'degree' => $this->degree,
            
            // keys
            'type' => $this->type,
            'type_id' => $this->type_id,

            'category_id' => $this->category_id,
            'category' => $this->category,

            'user_id' => $this->user_id,
            'user' => $this->user,

            'author' => $this->author,
            'author_id' => $this->author_id,
            
            // counts
            'downloads' => $this->downloads,
            'views' => $this->views,
            'reviews' => $this->reviews,
            'sumreviews' => $this->sumreviews,

            'image' => $this->image,
            'document' => $this->document,
        ];
    }
}
