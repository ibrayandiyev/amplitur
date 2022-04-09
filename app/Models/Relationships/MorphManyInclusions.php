<?php

namespace App\Models\Relationships;

use App\Models\Inclusion;

trait MorphManyInclusions
{
    public function inclusions()
    {
        return $this->morphToMany(Inclusion::class, 'inclusionable');
    }
}
