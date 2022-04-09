<?php

namespace App\Repositories;

use App\Models\HotelAccommodationStructure;

class HotelAccommodationStructureRepository extends Repository
{
    public function __construct(HotelAccommodationStructure $model)
    {
        $this->model = $model;
    }
}