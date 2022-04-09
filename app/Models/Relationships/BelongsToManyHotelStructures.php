<?php

namespace App\Models\Relationships;

use App\Models\HotelStructure;

trait BelongsToManyHotelStructures
{
    public function structures()
    {
        return $this->belongsToMany(HotelStructure::class, 'hotel_structure', 'hotel_id', 'hotel_structure_id');
    }
}