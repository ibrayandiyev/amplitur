<?php

namespace App\Models\Relationships;

use App\Models\HotelAccommodation;

trait HasManyHotelAccommodations
{
    public function accommodations()
    {
        return $this->hasMany(HotelAccommodation::class);
    }
}
