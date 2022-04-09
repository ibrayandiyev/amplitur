<?php

namespace App\Models\Relationships;

use App\Models\Booking;

trait HasManyBookings
{
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
