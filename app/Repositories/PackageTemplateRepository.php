<?php

namespace App\Repositories;

use App\Models\PackageTemplate;

class PackageTemplateRepository extends Repository
{
    public function __construct(PackageTemplate $model)
    {
        $this->model = $model;
    }

    /**
     * [first description]
     *
     * @return  [type]  [return description]
     */
    public function first(): PackageTemplate
    {
        return $this->model->first();
    }
}