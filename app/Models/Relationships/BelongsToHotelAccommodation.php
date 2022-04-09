<?php

namespace App\Models\Relationships;

use App\Models\HotelAccommodation;

trait BelongsToHotelAccommodation
{
    public function hotelAccommodation()
    {
        return $this->belongsTo(HotelAccommodation::class, 'hotel_accommodation_id');
    }
}