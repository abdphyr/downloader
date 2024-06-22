<?php

namespace App\Partials\Service\Traits;

use Closure;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;

trait ServiceModel
{
      /**
     * Obect of the model
     * @var mixed
     */
    protected $model;

     /**
     * Query builder for the model
     * @var Builder
     */
    protected $query;

    /**
     * For using query in anywhere !!!
     * @var ?Closure
     */
    public ?Closure $queryClosure = null;

    public function findById($id, $query = null)
    {
        if (!$query) $this->setQuery();
        $this->callQueryClosure();
        $model = $this->query->where($this->getId(), '=', $id)->first();
        return $model;
    }

    public function findBy($column, $value)
    {
        $this->setQuery();
        $model = $this->query->where($column, '=', $value)->first();
        return $model;
    }

    public function find($values)
    {
        if ($this->query === null) {
            $this->query = $this->model->query();
        }
        if (is_array($values)) {
            foreach ($values as $column => $value) {
                $this->query = $this->query->where($column, $value);
            }
            return $this->query->first();
        }
    } 


    public function callQueryClosure()
    {
        if ($this->queryClosure) {
            if ($this->queryClosure instanceof Closure) {
                call_user_func($this->queryClosure, $this->query);
            } else {
                throw new Exception($this->queryClosure . " is must be closure");
            }
        }
    }

    public function getTableColumns($reverse = true)
    {
        $cols = [];
        foreach ($this->model->getTableColumns() as $col) {
            if ($reverse) {
                $cols[$col] = '↑' . $col;
                $cols['-'.$col] = '↓' . $col;
            } else {
                $cols['↑' . $col] = $col;
                $cols['↓' . $col] = '-'.$col;
            }
        }
        return $cols;
    }

    public function getModelFields()
    {
        return $this->model->getTableColumns();
    }

    public function specialFilter()
    {
    }

    public function specialSort()
    {
    }   
    
    public function getModel()
    {
        return $this->model;
    }
   
    protected function setQuery()
    {
        $this->query = $this->model->query();
        return $this->query;
    }
    
    public function unsetQuery()
    {
        $this->query = null;
    }

    public function getQuery($new = false)
    {
        if (!$new && $this->query) {
            return $this->query;
        }
        return $this->setQuery();
    }

    public function exists($id)
    {
        try {
            return $this->setQuery()->where($this->model->getTable(), '=', $id)->exists();
        } catch (QueryException $e) {
            throw new \Error("Please give main operation column name " . static::class . '::$id constructor. Default column is id');
        }
    }

    public function existsByColumn($column, $value)
    {
        try {
            return $this->setQuery()->where($column, '=', $value)->exists();
        } catch (QueryException $e) {
            throw new \Error($e->getMessage(), $e->getCode() ?? 501);
        }
    }

    public function getModelName($object = null)
    {
        try {
            $arr = explode('\\', $object ? $object::class : $this->model::class);
            $name = end($arr);
        } catch (\Throwable $th) {
            $arr = explode('\\', static::class);
            $name = str_replace('Service', '', end($arr));
        }
        return Str::lower(Str::snake($name));
    } 
}