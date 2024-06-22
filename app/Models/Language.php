<?php

namespace App\Models;

class Language extends BaseModel
{
    protected $fillable = [
        'code',
        'name',
        'is_active',
        'created_by',
        'updated_by'
    ];
}
