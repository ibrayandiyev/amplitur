<?php

namespace App\Repositories\Concerns;

use DB;
use Illuminate\Database\Eloquent\Model;

trait ActionDelete
{
    /**
     * Delete a resource
     *
     * @param   Model  $resource
     *
     * @return  Model
     */
    public function delete(Model $resource)
    {
        $resource = DB::transaction(function () use ($resource) {
            $resource = $this->onBeforeDelete($resource);
            $resource->delete();
            $resource = $this->onAfterDelete($resource);
            return $resource;
        });

        return $resource;
    }

    /**
     * Delete many resources at once using IDs
     *
     * @param   array  $resourcesIds
     *
     * @return  void
     */
    public function deleteMany(array $resourcesIds)
    {
        DB::transaction(function () use ($resourcesIds) {
            foreach ($resourcesIds as $resourceId) {
                $resource = $this->model->find($resourceId);

                if (empty($resource)) {
                    continue;
                }

                $resource->delete();
            };
        });
    }

    /**
     * Event called on before delete
     *
     * @return  Model
     */
    public function onBeforeDelete(Model $resource): Model
    {
        return $resource;
    }

    /**
     * Event called on after delete
     *
     * @return  Model
     */
    public function onAfterDelete(Model $resource): Model
    {
        return $resource;
    }
}
