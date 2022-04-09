<?php

namespace App\Models\Relationships;

use App\Models\Additional;

trait HasManyAdditionals
{
    public function additionals()
    {
        return $this->hasMany(Additional::class);
    }
}
