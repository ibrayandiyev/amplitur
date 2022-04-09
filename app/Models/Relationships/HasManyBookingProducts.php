<?php

namespace App\Models\Relationships;

use App\Models\BookingProduct;

trait HasManyBookingProducts
{
    public function bookingProducts()
    {
        return $this->hasMany(BookingProduct::class);
    }
}
