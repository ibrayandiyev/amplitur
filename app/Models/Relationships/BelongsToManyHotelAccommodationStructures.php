<?php

namespace App\Models\Relationships;

use App\Models\HotelAccommodationStructure;

trait BelongsToManyHotelAccommodationStructures
{
    public function structures()
    {
        return $this->belongsToMany(HotelAccommodationStructure::class, 'hotel_accommodations_structure', 'hotel_accommodation_id', 'hotel_accommodation_structure_id');
    }
}