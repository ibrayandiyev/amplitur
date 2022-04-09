<?php

namespace App\Models\Relationships;

use App\Models\HotelOffers;

trait BelongsToHotelOffer
{
    public function hotelOffer()
    {
        return $this->belongsTo(HotelOffers::class, 'hotel_offers_id');
    }
}