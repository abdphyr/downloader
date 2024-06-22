<?php

namespace App\Models;

use App\Traits\HasTranslation;

class Category extends BaseModel
{
	use HasTranslation;

	public $translationClass = CategoryTranslation::class;

    protected $guarded = [];

    protected $fillable = [
        'parent_id',
    ];

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }
}