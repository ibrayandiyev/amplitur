<?php

namespace App\Models\Relationships;

use App\Models\Contact;

trait MorphOneContact
{
    public function contact()
    {
        return $this->morphOne(Contact::class, 'contactable');
    }
}
