<?php

namespace App\Models\Relationships;

use App\Models\Package;

trait BelongsToPackage
{
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}