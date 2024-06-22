<?php

namespace App\Models;

use App\Traits\HasTranslation;

class SubjectType extends BaseModel
{
	use HasTranslation;

	public $translationClass = SubjectTypeTranslation::class;

    protected $guarded = [];

    protected $fillable = [
        
    ];
}