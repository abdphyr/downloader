<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;

trait ActionResource
{
    public $action;

    public function __construct($resource, $index = 0)
    {
        parent::__construct($resource, $index);
        $this->action = $this->detectAction();
    }

    public abstract function base();

    public function toArray($request)
    {
        if (method_exists(static::class, $this->action)) {
            return array_merge($this->base(), $this->{$this->action}());
        }
        return $this->base();
    }

    protected function detectAction()
    {
        $action = explode('@', Route::currentRouteAction());
        if (isset($action[1])) {
            return $action[1];
        }
    }
}
