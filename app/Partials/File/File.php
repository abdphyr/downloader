<?php

namespace App\Partials\File;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    const FILE = 1;
    const FILES = 2;

    protected $fillable = [
        'path',
        'info',
        'fileable_type',
        'fileable_id',
        'type'
    ];  

    public function fileable() 
    {
        return $this->morphTo();
    }
}