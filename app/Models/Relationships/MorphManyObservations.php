<?php

namespace App\Models\Relationships;

use App\Models\Observation;

trait MorphManyObservations
{
    public function observations()
    {
        return $this->morphToMany(Observation::class, 'observationable');
    }
}
