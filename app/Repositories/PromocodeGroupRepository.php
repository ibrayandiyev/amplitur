<?php

namespace App\Repositories;

use App\Models\PromocodeGroup;

class PromocodeGroupRepository extends Repository
{
    public function __construct(PromocodeGroup $model)
    {
        $this->model = $model;
    }
}