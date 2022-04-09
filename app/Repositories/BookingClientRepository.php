<?php

namespace App\Repositories;

use App\Models\BookingClient;

class BookingClientRepository extends Repository
{
    public function __construct(BookingClient $model)
    {
        $this->model = $model;
    }
}