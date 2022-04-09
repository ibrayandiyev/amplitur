<?php

namespace App\Models\Relationships;

use App\Models\Hotel;

trait BelongsToHotel
{
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}