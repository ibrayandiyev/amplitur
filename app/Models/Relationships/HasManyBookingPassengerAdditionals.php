<?php

namespace App\Models\Relationships;

use App\Models\BookingPassengerAdditional;

trait HasManyBookingPassengerAdditionals
{
    public function bookingPassengerAdditionals()
    {
        return $this->hasMany(BookingPassengerAdditional::class);
    }

    public function bookingPassengerAdditionalIds()
    {
        return $this->hasMany(BookingPassengerAdditional::class, "additional_id", "id");
    }
}
