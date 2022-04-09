<?php

namespace App\Repositories;

use App\Models\BookingOffer;

class BookingOfferRepository extends Repository
{
    public function __construct(BookingOffer $model)
    {
        $this->model = $model;
    }
}