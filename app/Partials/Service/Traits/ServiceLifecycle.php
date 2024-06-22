<?php

namespace App\Partials\Service\Traits;

use Closure;
use Exception;

trait ServiceLifecycle
{
        /**
     * For excecuting some logic before main operation  !!!
     * @var ?Closure
     */
    protected ?Closure $executedCallback = null;

    /**
     *For excecuting some logic after main operation is done succesfully !!!
     * @var ?Closure
     */
    protected ?Closure $succedCallback = null;

    /**
     *For excecuting some logic before main operation is fail !!!
     * @var ?Closure
     */
    protected ?Closure $failedCallback = null;

     /**
     *For excecuting some logic action is called !!!
     * @var ?Closure
     */
    protected ?Closure $mountedCallback = null;


    public function executed(Closure $callback)
    {
        if ($callback instanceof Closure) {
            $this->executedCallback = $callback;
        } else {
            throw new Exception($callback . " is must be closure");
        }
        return $this;
    }

    protected function onExecuted(...$info)
    {
        if ($this->executedCallback) {
            return call_user_func($this->executedCallback, ...$info);
        }
    }

    public function getExecutedCallback()
    {
        return $this->executedCallback;
    }

    public function succed(Closure $callback)
    {
        if ($callback instanceof Closure) {
            $this->succedCallback = $callback;
        } else {
            throw new Exception($callback . " is must be closure");
        }
        return $this;
    }

    public function getSuccedCallback()
    {
        return $this->succedCallback;
    }

    protected function onSucced(...$info)
    {
        if ($this->succedCallback) {
            return call_user_func($this->succedCallback, ...$info);
        }
    }

    public function failed(Closure $callback)
    {
        if ($callback instanceof Closure) {
            $this->failedCallback = $callback;
        } else {
            throw new Exception($callback . " is must be closure");
        }
        return $this;
    }

    public function getFailedCallback()
    {
        return $this->failedCallback;
    }

    protected function onFailed(...$info)
    {
        if ($this->failedCallback) {
            return call_user_func($this->failedCallback, ...$info);
        }
    }

    public function mounted(Closure $callback)
    {
        if ($callback instanceof Closure) {
            $this->mountedCallback = $callback;
        } else {
            throw new Exception($callback . " is must be closure");
        }
        return $this;
    }

    public function getMountedCallback()
    {
        return $this->mountedCallback;
    }

    protected function onMounted(...$info)
    {
        if ($this->mountedCallback) {
            return call_user_func($this->mountedCallback, ...$info);
        }
    }
}