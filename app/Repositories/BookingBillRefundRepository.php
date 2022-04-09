<?php

namespace App\Repositories;

use App\Models\BookingBillRefund;

class BookingBillRefundRepository extends Repository
{
    public function __construct(BookingBillRefund $model)
    {
        $this->model = $model;
    }
}