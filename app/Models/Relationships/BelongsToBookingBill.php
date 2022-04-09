<?php

namespace App\Models\Relationships;

use App\Models\BookingBill;

trait BelongsToBookingBill
{
    public function bookingBill()
    {
        return $this->belongsTo(BookingBill::class);
    }
}