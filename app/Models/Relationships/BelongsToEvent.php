<?php

namespace App\Models\Relationships;

use App\Models\Event;

trait BelongsToEvent
{
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}