<?php

namespace App\Repositories;

use App\Models\Bank;

class BankRepository extends Repository
{
    public function __construct(Bank $model)
    {
        $this->model = $model;
    }
}