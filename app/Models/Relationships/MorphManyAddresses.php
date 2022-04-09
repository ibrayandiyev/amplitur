<?php

namespace App\Models\Relationships;

use App\Models\Address;

trait MorphManyAddresses
{
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}
