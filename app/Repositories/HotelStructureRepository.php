<?php

namespace App\Repositories;

use App\Models\HotelStructure;

class HotelStructureRepository extends Repository
{
    public function __construct(HotelStructure $model)
    {
        $this->model = $model;
    }
}