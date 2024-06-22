<?php

namespace App\Partials\File;

interface WithFile
{
    /**
     * @return array
     */
    public function fileTypes();
    
    /**
     * @return array
     */
    public function fileSettings();
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files();
    
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function file();
}
