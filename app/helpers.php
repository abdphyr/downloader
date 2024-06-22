<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;

if (!function_exists("message")) {
    function message($message, $status = 200)
    {
        return response()->json(['message' => $message], $status);
    }
}

if (!function_exists("parseToRelation")) {
    function parseToRelation($relations = null)
    {
        $parsed = [];
        foreach ($relations as $key => $relation) {
            if (is_string($key)) {
                if ($relation instanceof Closure) $parsed[$key] = $relation;
                else if (is_array($relation)) $parsed[$key] = makeWithRelationQuery($relation);
            } else $parsed[] = $relation;
        }
        return $parsed;
    }
}

if (!function_exists("makeWithRelationQuery")) {
    function makeWithRelationQuery($relation)
    {
        $selects = [];
        $withs = [];
        if (empty($relation)) return fn ($q) => $q->select('*');
        $order = false;
        $closures = [];
        foreach ($relation as $key => $value) {
            if (is_numeric($key) && $value instanceof Closure) {
                $closures[] = $value;
                continue;
            }
            if ($key === 'order') {
                if (is_string($value) && !empty($value)) $order = $value;
                continue;
            }
            if (is_string($key)) {
                if ($value instanceof Closure) $withs[$key] = $value;
                else if (is_array($value)) $withs[$key] = makeWithRelationQuery($value);
            } else {
                if (str_contains($value, '|')) {
                    $selects[] = explode("|", $value)[0];
                    $order = $value;
                } else $selects[] = $value;
            }
        }
        return function ($q) use ($selects, $withs, $order, $closures) {
            if ($order) {
                $sort = explode('|', $order);
                $use = isset($sort[1]) && (strtolower($sort[1]) === 'asc' || strtolower($sort[1]) === 'desc');
                $q->orderBy($sort[0], $use ? strtolower($sort[1]) : 'asc');
            }
            if (empty($selects)) $q->select('*');
            else $q->selectRaw(implode(',', $selects));

            if (!empty($withs)) $q->with($withs);
            foreach ($closures as $closure) $closure($q);
        };
    }
}

if (!function_exists('val')) {
    function val($obj, $key, $default = null)
    {
        if (is_array($obj)) {

            return Arr::get($obj, $key, $default);

        } else if(str_contains($key, '.') && ($arr = explode('.', $key))) {

            $first = array_shift($arr);
            $firstProp = val($obj, $first, $default);
            return val($firstProp, implode('.', $arr), $default);

        } else if  (is_object($obj)) {

            if ($obj instanceof ArrayAccess) {
                return isset($obj[$key]) ? $obj[$key] : $default;
            }
            return isset($obj->{$key}) ? $obj->{$key} : $default;
            
        } else return $default;
    }
}


if (!function_exists('getProp')) {
    function getProp($data, $key, $default = null)
    {
        if(str_contains($key, '.') && ($arr = explode('.', $key))) {
            $first = array_shift($arr);
            $firstProp = getProp($data, $first, $default);
            return getProp($firstProp, implode('.', $arr), $default);
        }
        if (is_array($data)) return isset($data[$key]) ? $data[$key] : $default;
        else if  (is_object($data)) {
            if ($data instanceof Collection || $data instanceof SupportCollection) {
                return isset($data[$key]) ? $data[$key] : $default;
            }
            return isset($data->{$key}) ? $data->{$key} : $default;
        }
        else return $default;
    }
}

if (!function_exists('fileFinder')) {
    function fileFinder(string $name, string $path, string|null $namespace)
    {
        $items = scandir($path);
        $results = [];
        foreach ($items as $item) {
            if ($item == '.' || $item == '..') continue;
            if ($item == $name . '.php') {
                $results[] = $namespace ? "$namespace\\$name" : $name;
            }
            if (is_dir("$path/$item")) {
                $dirResults = fileFinder(name: $name, path: "$path/$item", namespace: "$namespace\\$item");
                $results = array_merge($results, $dirResults);
            }
        }
        return $results;
    }
}

if(!function_exists('cacheByUri')) {
    function cacheByUri(Closure $executer, $default = null, $uri = null)
    {
        try {
            $uri = $uri ?? request()->getRequestUri();
            if ($data = Cache::store('redis')->get($uri)) return $data;
            else if ($data = $executer()) {
                if (is_array($data)) $data = json_encode($data);
                Cache::store('redis')->put($uri, $data, 3600);
                return $data;
            } else return $default;
        } catch (QueryException $e) {
            throw new Error($e->getMessage());
        } catch (\Throwable $th) {
            if($data = $executer()) return $data;
            else return $default;
        }
    }
}

// if(!function_exists('cacheByUri')) {
//     function cacheByUri(Closure $executer, $default = null, $uri = null)
//     {
//         try {
//             $uri = $uri ?? request()->getRequestUri();
//             if ($cache = Redis::get($uri)) {
//                 $cache = json_decode($cache);
//                 if (($class = getProp($cache, 'class') && ($data = getProp($cache, 'data')))) {
//                     if (in_array($class, [Paginator::class, LengthAwarePaginator::class, CursorPaginator::class])) {
//                         return Container::getInstance()->makeWith($class, [
//                             'items' => getProp($data, 'items', []), 
//                             'total' => getProp($data, 'total', 0), 
//                             'perPage' => getProp($data, 'perPage', 0), 
//                             'currentPage' => getProp($data, 'currentPage', 0), 
//                             'options' => getProp($data, 'options', [])
//                         ]);
//                     } else if ($class = Collection::class) {
//                         return new Collection($data);
//                     } else return $data;
//                 }
//             }
//             else if ($data = $executer()) {
//                 $cache = [];
//                 if (is_object($data)) {
//                     $cache['class'] = $data::class;
//                 }
//                 $cache['data'] = $data;
//                 Redis::set($uri, json_encode($cache));
//                 return $data;
//             } else return $default;
//         } catch (QueryException $e) {
//             throw new Error($e->getMessage());
//         } catch (\Throwable $th) {
//             if($data = $executer()) return $data;
//             else return $default;
//         }
//     }
// }