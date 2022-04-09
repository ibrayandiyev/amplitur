<?php

namespace App\Models\Relationships;

use App\Models\BookingPassenger;

trait BelongsToBookingPassenger
{
    public function bookingPassenger()
    {
        return $this->belongsTo(BookingPassenger::class);
    }
}