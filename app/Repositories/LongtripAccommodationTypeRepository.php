<?php

namespace App\Repositories;

use App\Models\LongtripAccommodationType;

class LongtripAccommodationTypeRepository extends Repository
{
    public function __construct(LongtripAccommodationType $model)
    {
        $this->model = $model;
    }
}