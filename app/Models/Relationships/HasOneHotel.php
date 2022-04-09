<?php

namespace App\Models\Relationships;

use App\Models\Hotel;

trait HasOneHotel
{
    public function hotel()
    {
        return $this->hasOne(Hotel::class, "id", "hotel_id");
    }
}
