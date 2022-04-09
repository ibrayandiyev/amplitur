<?php

namespace App\Repositories;

use App\Models\LongtripHotelLabel;
use App\Models\Offer;

class LongtripHotelLabelRepository extends Repository
{
    /**
     * @var Offer
     */
    public $offer;

    public function __construct(LongtripHotelLabel $model)
    {
        $this->model = $model;
    }
}