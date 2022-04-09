<?php

namespace App\Models\Relationships;

use App\Models\Booking;

trait BelongsToBooking
{
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function targetBooking()
    {
        return $this->belongsTo(Booking::class, "target_booking_id", "id");
    }
}