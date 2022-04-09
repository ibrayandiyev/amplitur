<?php

namespace App\Models\Relationships;

use App\Models\Exclusion;

trait HasManyExclusions
{
    public function exclusions()
    {
        return $this->hasMany(Exclusion::class);
    }
}
