<?php

namespace App\Partials\File;

trait HasFile
{
    public bool $fileable = true;

    public $fileModel = File::class;

    public function fileTypes()
    {
        return [
            File::FILE => 'file',
            File::FILES => 'files',
        ];
    }

    public function fileSettings()
    {
        return [];
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
