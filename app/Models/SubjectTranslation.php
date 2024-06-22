<?php

namespace App\Models;

use App\Models\BaseTranslation;

class SubjectTranslation extends BaseTranslation
{
    protected $table = 'subject_translations';

    public $timestamps = false;

    protected $guarded = [];
    
}
