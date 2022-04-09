<?php

namespace App\Repositories\Concerns;

use App\Models\Common\Customer;
use DB;
use Illuminate\Database\Eloquent\Model;

trait ActionStore
{
    /**
     * Store a resource
     *
     * @param   array  $attributes
     *
     * @return  Model
     */
    public function store(array $attributes): Model
    {
        $resource = DB::transaction(function () use ($attributes) {
            $attributes = $this->onBeforeStore($attributes);
            $resource = $this->make($attributes);
            $resource = $this->save($resource);
            $resource = $this->onAfterStore($resource, $attributes);
            $resource->refresh();
            return $resource;
        });

        return $resource;
    }

    /**
     * Store a resource
     *
     * @param   array  $attributes
     *
     * @return  Model
     */
    public function storeFirstOrCreate(array $attributes): Model
    {
        $resource = DB::transaction(function () use ($attributes) {
            $attributes = $this->onBeforeStore($attributes);
            $resource = $this->make($attributes);
            $resource = $this->firstOrCreate($resource, $attributes);
            $resource = $this->onAfterStore($resource, $attributes);
            $resource->refresh();
            return $resource;
        });

        return $resource;
    }

    /**
     * Make a new instance of the model filling it
     *
     * @param   array  $attributes
     *
     * @return  Model
     */
    public function make(array $attributes): Model
    {
        $resource = new $this->model;
        $resource = $resource->fill($attributes);

        return $resource;
    }

    /**
     * Save resource
     *
     * @param   Model  $resource
     *
     * @return  Model
     */
    public function save(Model $resource): Model
    {
        $resource->save();

        return $resource;
    }

    /**
     * Save resource
     *
     * @param   Model  $resource
     *
     * @return  Model
     */
    public function firstOrCreate(Model $resource, array $attributes): Model
    {
        $resource->firstOrCreate($attributes);

        return $resource;
    }

    /**
     * Update or Save resource
     *
     * @param   Model  $resource
     *
     * @return  Model
     */
    public function updateOrCreate(Model $resource, array $_conditions, array $attributes): Model
    {
        $resource = $resource->updateOrCreate($_conditions, $attributes);

        return $resource;
    }

    /**
     * Event called on before store
     *
     * @return  array
     */
    public function onBeforeStore(array $attributes): array
    {
        return $attributes;
    }

    /**
     * Event called on after store
     *
     * @return  array
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        return $resource;
    }
}
