<?php

namespace App\Models\Relationships;

use App\Models\Inclusion;

trait HasManyInclusions
{
    public function inclusions()
    {
        return $this->hasMany(Inclusion::class);
    }
}
