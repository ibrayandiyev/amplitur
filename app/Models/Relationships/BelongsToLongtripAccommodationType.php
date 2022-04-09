<?php

namespace App\Models\Relationships;

use App\Models\LongtripAccommodationType;

trait BelongsToLongtripAccommodationType
{
    public function type()
    {
        return $this->belongsTo(LongtripAccommodationType::class, 'longtrip_accommodation_type_id');
    }
}