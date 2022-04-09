<?php

namespace App\Models\Relationships;

use App\Models\AdditionalGroup;

trait HasManyAdditionalGroups
{
    public function additionalGroups()
    {
        return $this->hasMany(AdditionalGroup::class);
    }
}
