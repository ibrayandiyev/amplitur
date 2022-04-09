<?php

namespace App\Models\Relationships;

use App\Models\Prebooking;

trait HasManyPrebookings
{
    public function prebookings()
    {
        return $this->belongsTo(Prebooking::class);
    }
}