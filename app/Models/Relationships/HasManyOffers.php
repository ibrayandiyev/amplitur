<?php

namespace App\Models\Relationships;

use App\Models\Event;
use App\Models\Offer;

trait HasManyOffers
{
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
