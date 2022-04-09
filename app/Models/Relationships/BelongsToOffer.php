<?php

namespace App\Models\Relationships;

use App\Models\Offer;

trait BelongsToOffer
{
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}