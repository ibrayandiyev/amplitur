<?php

namespace App\Models\Relationships;

use App\Models\LongtripAccommodationHotel;

trait HasOneLongtripAccommodationHotel
{
    public function longtripAccommodationHotel()
    {
        return $this->hasOne(LongtripAccommodationHotel::class);
    }
}