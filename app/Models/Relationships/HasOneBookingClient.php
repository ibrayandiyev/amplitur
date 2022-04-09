<?php

namespace App\Models\Relationships;

use App\Models\BookingClient;

trait HasOneBookingClient
{
    public function bookingClient()
    {
        return $this->hasOne(BookingClient::class);
    }
}
