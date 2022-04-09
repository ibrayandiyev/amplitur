<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait ActionFindBy
{
    /**
     * Find a resource
     *
     * @param   array  $condition
     *
     * @return  Model|Collection|null
     */
    public function findBy($condition=null)
    {
        $resource = $this->model->where($condition)->first();

        return $resource;
    }
}
