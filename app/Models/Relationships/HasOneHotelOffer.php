<?php

namespace App\Models\Relationships;

use App\Models\HotelOffers;

trait HasOneHotelOffer
{
    public function hotelOffer()
    {
        return $this->hasOne(HotelOffers::class);
    }
}
