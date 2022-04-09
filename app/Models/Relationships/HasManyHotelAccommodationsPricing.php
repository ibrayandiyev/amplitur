<?php

namespace App\Models\Relationships;

use App\Models\HotelAccommodationsPricing;

trait HasManyHotelAccommodationsPricing
{
    public function hotelAccommodationsPricings()
    {
        return $this->hasMany(HotelAccommodationsPricing::class);
    }
}
