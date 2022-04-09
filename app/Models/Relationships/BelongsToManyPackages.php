<?php

namespace App\Models\Relationships;

use App\Models\Package;

trait BelongsToManyPackages
{
    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }
}