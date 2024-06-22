<?php

namespace App\Partials\Service;

use App\Partials\Service\Traits\ServiceLifecycle;
use App\Partials\Service\Traits\ServiceModel;
use App\Partials\Service\Traits\ServiceProperties;
use App\Partials\Service\Traits\ServiceResources;
use App\Partials\Service\Traits\ServiceResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Closure;

abstract class CoreService
{
    use ServiceLifecycle;
    use ServiceResponse;
    use ServiceResources;
    use ServiceModel;
    use ServiceProperties;

    /**
     * Checking authorizing is possible
     * @var bool
     */
    public bool $needAuthorization = true;

    protected function authorizeMethod($method, $model = null)
    {
        if ($this->needAuthorization) {
            if (is_null($model) && in_array($method, $this->authorizeMethods)) {
                Gate::authorize($method, $this->model);
            } elseif (in_array($method, $this->authorizeMethods)) {
                Gate::authorize($method, $model);
            }
        }
        $this->needAuthorization = true;
    }

    public function isWithTrashed()
    {
        return $this->withTrashed;
    }

    public function getId()
    {
        return $this->id;
    }

    protected function checkInitialized($property, $class = null, $object = null)
    {
        return (new \ReflectionProperty($class ?? static::class, $property))->isInitialized($object ?? $this);
    }

    public function withTransaction(Closure $executer = null, Closure $catch = null, Closure $then = null)
    {
        $data = null;
        try {
            DB::beginTransaction();
            if ($executer && $executer instanceof Closure) {
                $data = $executer();
            }
            DB::commit();
            if ($then && $then instanceof Closure) {
                $then($data);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            if ($catch && $catch instanceof Closure) {
                $catch($th->getMessage(), 500);
            }
        }
    }

    public function query(Closure $callback)
    {
        $this->queryClosure = $callback;
        return $this;
    }

    public function with($willParseToRelation)
    {
        if (is_string($willParseToRelation)) {
            $willParseToRelation = [$willParseToRelation];
        }
        $this->willParseToRelation = $willParseToRelation;
        return $this;
    }
}
