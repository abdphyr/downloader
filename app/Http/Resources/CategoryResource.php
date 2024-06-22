<?php

namespace App\Http\Resources;

use App\Traits\ActionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    use ActionResource;
    
    public function base()
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'translation' => $this?->translation,
            'translations' => $this?->translations,
            'children' => $this->children
        ];
    }
}
