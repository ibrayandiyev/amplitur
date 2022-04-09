<?php

namespace App\Models\Relationships;

use App\Models\BookingBillRefund;

trait HasManyBookingBillRefunds
{
    public function bookingBillRefunds()
    {
        return $this->hasMany(BookingBillRefund::class);
    }
}
