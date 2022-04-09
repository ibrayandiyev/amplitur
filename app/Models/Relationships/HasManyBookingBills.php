<?php

namespace App\Models\Relationships;

use App\Models\BookingBill;

trait HasManyBookingBills 
{
    public function bookingBills()
    {
        return $this->hasMany(BookingBill::class);
    }
}
