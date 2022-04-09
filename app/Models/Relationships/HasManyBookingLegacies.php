<?php

namespace App\Models\Relationships;

use App\Models\BookingLegacies;

trait HasManyBookingLegacies
{
    public function bookingLegacies()
    {
        return $this->hasMany(BookingLegacies::class);
    }
}
