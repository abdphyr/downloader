<?php

namespace App\Models;

use App\Models\BaseTranslation;

class SubjectTypeTranslation extends BaseTranslation
{
    protected $table = 'subject_type_translations';

    public $timestamps = false;

    protected $guarded = [];
}
