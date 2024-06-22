<?php

namespace App\Partials\Service\Traits;

use App\Partials\Service\Helpers\CreateModelService;
use App\Partials\Service\Helpers\DeleteModelService;
use App\Partials\Service\Helpers\ReadModelService;
use App\Partials\Service\Helpers\UpdateModelService;

trait HelperResolver
{
    protected ?CreateModelService $createModelService = null; // C
    protected ?ReadModelService   $readModelService   = null; // R
    protected ?UpdateModelService $updateModelService = null; // U
    protected ?DeleteModelService $deleteModelService = null; // D

    protected function createModelService()
    {
        return $this->createModelService ??= new CreateModelService($this);
    }
    
    protected function readModelService()
    {
        return $this->readModelService ??= new ReadModelService($this);
    }

    protected function updateModelService()
    {
        return $this->updateModelService ??= new UpdateModelService($this);
    }

    protected function deleteModelService()
    {
        return $this->deleteModelService ??= new DeleteModelService($this);
    }
}
