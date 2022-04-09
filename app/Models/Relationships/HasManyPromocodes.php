<?php

namespace App\Models\Relationships;

use App\Models\Promocode;

trait HasManyPromocodes
{
    public function promocodes()
    {
        return $this->hasMany(Promocode::class);
    }
}
