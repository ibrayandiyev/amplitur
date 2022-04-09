<?php

namespace App\Models\Relationships;

use App\Models\Exclusion;

trait MorphManyExclusions
{
    public function exclusions()
    {
        return $this->morphToMany(Exclusion::class, 'exclusionable');
    }
}
