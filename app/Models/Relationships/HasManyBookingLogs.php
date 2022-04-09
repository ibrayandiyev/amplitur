<?php

namespace App\Models\Relationships;

use App\Models\BookingLog;

trait HasManyBookingLogs
{
    public function bookingLogs()
    {
        return $this->hasMany(BookingLog::class, "target_booking_id");
    }
}
