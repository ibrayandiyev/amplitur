<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository extends Repository
{
    public function __construct(Currency $model)
    {
        $this->model = $model;
    }

    /**
     * [findByCode description]
     *
     * @param   string    $code  [$code description]
     *
     * @return  Currency         [return description]
     */
    public function findByCode(string $code): ?Currency
    {
        return $this->model->where('code', $code)->first();
    }
}