<?php

namespace App\Models\Relationships;

use App\Models\BookingVoucher;

trait HasManyBookingVouchers
{
    public function bookingVouchers()
    {
        return $this->hasMany(BookingVoucher::class);
    }
}
