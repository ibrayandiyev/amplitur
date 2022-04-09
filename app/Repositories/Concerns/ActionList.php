<?php

namespace App\Repositories\Concerns;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait ActionList
{
    /**
     * List all items of a resource
     *
     * @return  Collection
     */
    public function list(int $paginate = null, array $_params = null): Collection
    {
        $collection = $this->model;

        $collection = $this->onBeforeListFilter($collection);

        if($_params != null && is_array($_params)){
            $collection = $collection->filter($_params);
        }
        
        if ($paginate) {
            $collection = $collection->paginate($paginate)->getCollection();
        } else {
            $collection = $collection->get();
        }

        $collection = $this->onAfterList($collection);

        return $collection;
    }

    /**
     * Event called on after list
     *
     * @param   Collection  $collection
     *
     * @return  Collection
     */
    protected function onAfterList(Collection $collection): Collection
    {
        return $collection;
    }

    /**
     * Event called on before model list
     *
     * @param   BaseModel  $model
     *
     * @return  BaseModel
     */
    protected function onBeforeListFilter($model)
    {
        return $model;
    }
}
