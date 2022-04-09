<?php

namespace App\Models\Relationships;

use App\Models\Additional;

trait MorphManyAdditionals
{
    public function additionals()
    {
        return $this->morphToMany(Additional::class, 'additionalable')->withPivot('id', 'additionalable_id');
    }
}
