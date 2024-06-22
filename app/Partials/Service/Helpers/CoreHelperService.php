<?php

namespace App\Partials\Service\Helpers;

use App\Partials\Service\CoreService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Closure;

abstract class CoreHelperService
{
    protected CoreService $coreService;
    protected Model $model;
    protected ?Builder $query = null;

    public function __construct(CoreService $coreService)
    {
        $this->coreService = $coreService;
        $this->model = $coreService->getModel();
    }

    protected function setQuery()
    {
        $this->query = $this->coreService->getQuery(true);
        return $this->query;
    }

    protected function unsetQuery()
    {
        $this->coreService->unsetQuery();
        $this->query = null;
    }

    public function findById($id, $query = null)
    {
        if (!$query) $this->setQuery();
        $this->coreService->callQueryClosure();
        $model = $this->query->where($this->coreService->getId(), '=', $id)->first();
        return $model;
    }

    protected function mounted(?Closure $mounted = null, ...$info)
    {
        if ($mounted && $mounted instanceof Closure) {
            $mounted(...$info);
        }
        if ($callback = $this->coreService->getMountedCallback()) {
            call_user_func($callback, ...$info);
        }
    }

    protected function executed(?Closure $executed = null, ...$info)
    {
        if ($executed && $executed instanceof Closure) {
            $executed(...$info);
        }
        if ($callback = $this->coreService->getExecutedCallback()) {
            call_user_func($callback, ...$info);
        }
    }

    protected function succed(?Closure $succed = null, ...$info)
    {
        $result = null;
        if ($succed && $succed instanceof Closure) {
            $result = $succed(...$info);
        }
        if ($callback = $this->coreService->getSuccedCallback()) {
            $result = call_user_func($callback, ...$info);
        }
        return $result;
    }

    protected function failed(?Closure $failed = null, ...$info)
    {
        $result = null;
        if ($failed && $failed instanceof Closure) {
            $result = $failed(...$info);
        }
        if ($callback = $this->coreService->getFailedCallback()) {
            $result = call_user_func($callback, ...$info);
        }
        return $result;
    }
}
