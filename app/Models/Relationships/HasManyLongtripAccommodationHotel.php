<?php

namespace App\Models\Relationships;

use App\Models\LongtripAccommodationHotel;

trait HasManyLongtripAccommodationHotel
{
    public function longtripAccommodationHotels()
    {
        return $this->hasMany(LongtripAccommodationHotel::class);
    }
}