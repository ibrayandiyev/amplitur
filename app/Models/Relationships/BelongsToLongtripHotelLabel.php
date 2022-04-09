<?php

namespace App\Models\Relationships;

use App\Models\LongtripHotelLabel;

trait BelongsToLongtripHotelLabel
{
    public function longtripHotelLabel()
    {
        return $this->belongsTo(LongtripHotelLabel::class, 'longtrip_hotel_label_id');
    }
}