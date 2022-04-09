<?php

namespace App\Models\Relationships;

use App\Models\BookingOffer;

trait HasOneBookingOffer
{
    public function bookingOffer()
    {
        return $this->hasOne(BookingOffer::class);
    }
}
