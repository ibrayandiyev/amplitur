<?php

namespace App\Models\Relationships;

use App\Models\LongtripAccommodation;

trait BelongsToLongtripAccommodation
{
    public function longtripAccommodation()
    {
        return $this->belongsTo(LongtripAccommodation::class, 'longtrip_accommodation_id');
    }
}