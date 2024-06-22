<?php

namespace App\Models;

use App\Models\BaseTranslation;

class CategoryTranslation extends BaseTranslation
{
    protected $table = 'category_translations';

    public $timestamps = false;

    protected $guarded = [];
}
