<?php

namespace App\Models\Relationships;

use App\Models\Event;

trait HasManyEvents
{
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
