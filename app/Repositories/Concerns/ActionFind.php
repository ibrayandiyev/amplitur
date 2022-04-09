<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait ActionFind
{
    /**
     * Find a resource
     *
     * @param   int|array  $id
     *
     * @return  Model|Collection|null
     */
    public function find($id)
    {
        $resource = $this->model->find($id);

        return $resource;
    }
}
