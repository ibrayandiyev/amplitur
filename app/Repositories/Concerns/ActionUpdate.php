<?php

namespace App\Repositories\Concerns;

use App\Models\BookingPassengerAdditional;
use App\Models\Clinical\Worktime;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait ActionUpdate
{
    /**
     * Update a resource
     *
     * @param   Model  $resource
     * @param   array  $attributes
     *
     * @return  Model
     */
    public function update(Model $resource, array $attributes): Model
    {
        $resource = DB::transaction(function () use ($resource, $attributes) {
            $attributes = $this->unsetUnchangeableFields($attributes);
            $attributes = $this->onBeforeUpdate($resource, $attributes);
            $resource->fill($attributes);
            $resource->save();
            $resource = $this->onAfterUpdate($resource, $attributes);
            $resource->refresh();
            return $resource;
        });

        return $resource;
    }

    /**
     * [updateBatch description]
     *
     * @param   array  $attributes  [$attributes description]
     *
     * @return  [type]              [return description]
     */
    public function updateBatch(array $attributes)
    {
        foreach ($attributes as $id => $fields) {
            $resource = $this->find($id);

            foreach ($fields as $field => $value) {
                $resource->$field = $value;
            }

            $resource->save();
        }
    }

    /**
     * Event called on before update
     *
     * @param   Model  $resource
     * @param   array  $attributes
     * 
     * @return  array
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        return $attributes;
    }

    /**
     * Event called on after update
     *
     * @param   Model  $resource
     * @param   array  $attributes
     * 
     * @return  Model
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        return $resource;
    }

    /**
     * Unset unchangeable model fields
     *
     * @param   array  $attributes
     *
     * @return  array
     */
    public function unsetUnchangeableFields(array $attributes): array
    {
        if (is_null($this->model->unchangeableFields) || empty($this->model->unchangeableFields)) {
            return $attributes;
        }

        $filtredAttributes = [];

        if (is_countable($attributes)) {
            foreach ($attributes as $key => $value) {
                $filtredAttributes[$key] = $this->filterUnchangeableFields($value);
            }
        } else {
            $filtredAttributes = $this->filterUnchangeableFields($attributes);
        }

        return $filtredAttributes;
    }

    /**
     * Filter criteria to remove unchangeable fields
     *
     * @param   array  $attributes
     *
     * @return  array
     */
    protected function filterUnchangeableFields(array $attributes): array
    {
        $filtred = array_filter($attributes, function ($attribute) {
            return !in_array($attribute, $this->model->unchangeableFields);
        });
    
        return $filtred;
    }
}
