<?php

namespace App\Models\Relationships;

use App\Models\HotelAccommodationType;

trait BelongsToHotelAccommodationType
{
    public function type()
    {
        return $this->belongsTo(HotelAccommodationType::class, 'hotel_accommodation_type_id');
    }
}