<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageUpsertRequest;
use App\Services\EnumService;

class EnumController extends Controller
{

    public function __construct(protected EnumService $service)
    {
    }

    public function subjectStatus()
    {
        return $this->service->subjectStatus();
    }
    
    public function educationDegree()
    {
        return $this->service->educationDegree();
    }
}
