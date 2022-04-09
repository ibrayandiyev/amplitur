<?php

namespace App\Models\Relationships;

use App\Models\Address;

trait MorphOneAddress
{
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
