<?php

namespace App\Repositories;

use App\Models\PageGroup;

class PageGroupRepository extends Repository
{
    public function __construct(PageGroup $model)
    {
        $this->model = $model;
    }
}