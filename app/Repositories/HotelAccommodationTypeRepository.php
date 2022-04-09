<?php

namespace App\Repositories;

use App\Models\HotelAccommodationType;

class HotelAccommodationTypeRepository extends Repository
{
    public function __construct(HotelAccommodationType $model)
    {
        $this->model = $model;
    }
}