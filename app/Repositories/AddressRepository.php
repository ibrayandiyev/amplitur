<?php

namespace App\Repositories;

use App\Models\Address;

class AddressRepository extends Repository
{
    public function __construct(Address $model)
    {
        $this->model = $model;
    }
}