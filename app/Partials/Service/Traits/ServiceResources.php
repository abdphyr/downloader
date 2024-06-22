<?php

namespace App\Partials\Service\Traits;

trait ServiceResources
{
     /**
     * Model Resource class
     * @var JsonResource    
     */
    protected $resource, $showResource;

    /**
     * Model Resources
     * @var JsonResource[]
     */
    protected $resources, $showResources;

    protected function withResource($data, $isCollection = false)
    {
        if ($this->resource) {
            if ($isCollection) {
                return $this->resource::collection($data);
            } else {
                return $this->showResource ? $this->showResource::make($data) : $this->resource::make($data);
            }
        } else {
            return $data;
        }
    }

    public function toResource($data, $isList = false)
    {
        return $this->withResource($data, $isList);
    }

    public function changeResource(int|string $index)
    {
        if (is_string($index)) {
            $this->resource = $index;
            return $this;
        }
        if (isset($this->resources[$index])) {
            $this->resource = $this->resources[$index];
        }
        return $this;
    }

    public function changeShowResource(int|string $index)
    {
        if (is_string($index)) {
            $this->showResource = $index;
            return $this;
        }
        if (isset($this->showResources[$index])) {
            $this->showResource = $this->showResources[$index];
        }
        return $this;
    }
}
