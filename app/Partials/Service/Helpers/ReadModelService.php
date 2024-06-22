<?php

namespace App\Partials\Service\Helpers;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class ReadModelService extends CoreHelperService
{   
    public function getList($data = [], $limit = 10, $page = 1)
    {
        $this->setLocaleFromQuery();
        $this->setQuery();
        $needPagination = isset($data['pagination']) ? $data['pagination'] : 1;
        $page = isset($data['page']) ? $data['page'] : $page;
        $limit = isset($data['limit']) ? $data['limit'] : $limit;
        $this->query->with($this->coreService->relations + parseToRelation($this->coreService->willParseToRelation));
        $this->query->where($this->prepareConditions());
        $this->languageFilter();
        $this->filter($data);
        $this->sort($data);
        $this->coreService->specialFilter($this->query);
        $this->coreService->callQueryClosure($this->query);
        $this->coreService->specialSort($this->query);
        if ($needPagination) {
            $data = $this->query->paginate(perPage: $limit, page: $page);
        } else {
            $data = $this->query->get();
        }
        $this->unsetQuery();
        return $data;
    }

    public function getCount($data = [])
    {
        $this->setQuery();
        $this->query->with($this->coreService->relations + parseToRelation($this->coreService->willParseToRelation));
        $this->query->where($this->prepareConditions());
        $this->languageFilter();
        $this->filter($data);
        $this->sort($data);
        $this->coreService->specialFilter($this->query);
        $this->coreService->callQueryClosure($this->query);
        $this->coreService->specialSort($this->query);
        $count = $this->query->count();
        $this->unsetQuery();
        return $count;
    }

    public function getAll($data = [])
    {
        $this->setLocaleFromQuery();
        $this->setQuery();
        $this->query->with($this->coreService->relations + parseToRelation($this->coreService->willParseToRelation));
        $this->query->where($this->prepareConditions());
        $this->languageFilter($data);
        $this->filter($data);
        $this->sort($data);
        $this->coreService->specialFilter($this->query);
        $this->coreService->callQueryClosure($this->query);
        $this->coreService->specialSort($this->query);
        $data = $this->query->get();
        $this->unsetQuery();
        return $data;
    }

    public function show(
        mixed $id,
        ?Closure $succed = null,
        ?Closure $failed = null,
        ?Closure $executed = null,
        ?Closure $mounted = null
    ){
        try {
            $this->setLocaleFromQuery();
            $this->mounted($mounted, $id);
            $this->setQuery();
            $this->query->with($this->coreService->relations + parseToRelation($this->coreService->willParseToRelation));
            $this->query->where($this->prepareConditions());
            $this->coreService->callQueryClosure();
            if ($this->coreService->isWithTrashed()) $this->query->withTrashed();
            if (! ($model = $this->findById($id, $this->query))) {
                throw new \Exception($this->coreService->getModelName() . '_not_found', 404);
            }
            $this->executed($executed, $model);
            $this->unsetQuery();
            $this->succed($succed, $model, $id);
            return $model;
        } catch (\Throwable $th) {
            $this->failed($failed, $th, $id);
            return $th;
        }
    }

    protected function sort($request = [])
    {
        if ($sort = $this->request('sort', $request)) {
            foreach (explode(',', $sort) as $s) {
                $desc = str_starts_with($s, '-');
                $type = $desc ? 'DESC' : 'ASC';
                $field = $desc ? substr($s, 1) : $s;
                if ($this->columnExists($field)) {
                    $this->query->orderBy($this->model->getTable().".$field", $type);
                }
            }
        } elseif (!empty($this->coreService->defaultOrder)) {
            foreach ($this->coreService->defaultOrder as $order) {
                $this->query->orderBy($this->model->getTable() . '.' . $order['column'], $order['direction']);
            }
        }
    }

    protected function setLocaleFromQuery()
    {
        if ($code = request('lang')) {
            app()->setLocale($code);
        }
    }
    

    private function columnExists($column)
    {
        return Schema::connection($this->model->connection)->hasColumn($this->model->getTable(), $column);
    }

    public function filter($request = [])
    {
        if ($this->request('s', $request) !== null) {
            $this->globalSearch($request);
        }
        // model likable filters
        $this->query->where(function ($query) use ($request) {
            foreach ($this->coreService->likableFields as $field) {
                if ($this->request($field, $request) !== null) {
                    $query->where($field, 'ilike', '%' . $this->request($field, $request) . '%');
                }
            }
        });

        // translation likable filters
        $this->query->where(function ($query) use ($request) {
            if (!empty($this->translation())) {
                foreach ($this->coreService->translationFields as $field) {
                    if ($this->request($field, $request) !== null) {
                        $query->whereHas('translation', function ($query) use ($field, $request) {
                            $query->where($field, 'ilike', '%' . $this->request($field, $request) . '%');
                        });
                    }
                }
            }
        });

        // exact equal filters
        foreach ($this->coreService->equalableFields as $field) {
            if ($this->request($field, $request) !== null && $this->request($field, $request) != 'null') {
                $this->query->whereIn($field, explode(',', $this->request($field, $request)));
            }
        }

        // numeric interval filters
        foreach ($this->coreService->numericIntervalFields as $field) {
            if ($this->request($field, $request) !== null && str_contains($this->request($field, $request), '|')) {
                list($from, $to) = explode('|', $this->request($field, $request));
                $this->query->where(function ($query) use ($field, $from, $to) {
                    if ($from) $query->where($field, '>=', $from);
                    if ($to) $query->where($field, '<=', $to);
                });
            }
        }

        // date interval filters
        foreach ($this->coreService->dateIntervalFields as $field) {
            if ($this->request($field, $request) && str_contains($this->request($field, $request), '|')) {
                list($from, $to) = explode('|', $this->request($field, $request));
                $this->query->where(function ($query) use ($field, $from, $to) {
                    if ($from) $query->whereDate($field, '>=', $from);
                    if ($to) $query->whereDate($field, '<=', $to);
                });
            }
        }
    }

    protected function globalSearch($request)
    {
        $this->query->where(function ($query) use ($request) {
            // relation likeable 
            $this->relationLikableFilter($this->request('s', $request), $this->coreService->relationLikableFields, $query);

            // model likable fields
            foreach ($this->coreService->likableFields as $field) {
                $query->orWhere($field, 'ilike', '%' . $this->request('s', $request) . '%');
            }
            // transaltion likable fields
            if (!empty($this->translation())) {
                foreach ($this->coreService->translationFields as $field) {
                    $query->orWhereHas('translation', function ($query) use ($field, $request) {
                        $query->where($field, 'ilike', '%' . $this->request('s', $request) . '%');
                    });
                }
            }
        });
    }

    /**
     * Requestdan $keyni olish agar $request array berilgan bo'lsa undan olish
     * @param string $key 
     * @param array $request 
     */
    public function request($key, $request = [])
    {
        if (!empty($request) && isset($request[$key])) {
            return $request[$key];
        }
        return request($key);
    }

    /**
     * Service uchun relation columnlariga filter qo'shish funksiyasi
     *
     * @param string $search
     * @param array $likableRelations
     * @param Builder $query
     * @param string $method
     * @return void
     */
    public function relationLikableFilter($search, $likableRelations, $query, $method = 'orWhereHas')
    {
        foreach ($likableRelations as $relation => $field) {
            if (is_array($field)) {
                foreach ($field as $key => $value) {
                    if (is_string($key)) {
                        $query->{$method}($relation, function ($q) use ($key, $value, $search) {
                            $this->relationLikableFilter(search: $search, likableRelations: [$key => $value], query: $q, method: 'whereHas');
                        }); 
                    } else {
                        $query->{$method}($relation, function ($q) use ($value, $search) {
                            $q->where($value, 'ilike', '%' . $search . '%');
                        });
                    }
                }
            } else if (is_string($field)) {
                $query->{$method}($relation, function ($q) use ($field, $search) {
                    $q->where($field, 'ilike', '%' . $search . '%');
                });
            }
        }
    }
    

    public function languageFilter()
    {
        if (!empty($this->translation())) {
            $this->query->whereHas('translation', function ($query) {
                $query->where('language_code', app()->getLocale() ?? config('app.user_language'));
            });
        }
    }

    protected function prepareConditions()
    {
        if (!empty($this->model->getTable())) {
            return array_map(function ($condition) {
                if (!empty($condition)) {
                    $column = $condition[0];
                    if (!str_contains($column, $this->model->getTable())) {
                        $condition[0] = $this->model->getTable().".$column";
                    }
                }
                return $condition;
            }, $this->coreService->conditions);
        } else return $this->coreService->conditions;
    }

    public function translation()
    {
        return $this->model->translationClass ?? null;
    }
}
