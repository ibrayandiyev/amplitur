<?php

namespace App\Models\Relationships;

use App\Models\BookingPassenger;

trait HasManyBookingPassengers 
{
    public function bookingPassengers()
    {
        return $this->hasMany(BookingPassenger::class);
    }
}
