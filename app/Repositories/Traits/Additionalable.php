<?php

namespace App\Repositories\Traits;

use Illuminate\Database\Eloquent\Model;

trait Additionalable
{
    /**
     * Associate additionals info
     * @return void
     */
    public function associateAdditionals(Model $additionalable, $attributes)
    {
        $additionals = $this->getAdditionalsToAssociate($additionalable, $attributes);

        if (is_null($additionals)) {
            $additionalable->additionals()->sync([]);
            return;
        }

        if (isset($additionals[$additionalable->id])) {
            $additionalable->additionals()->sync($additionals[$additionalable->id]);
        }
    }

    /**
     * [getAdditionalsToAssociate description]
     *
     * @param   array  $attributes  [$attributes description]
     *
     * @return  [type]              [return description]
     */
    public function getAdditionalsToAssociate(Model $additionalable, array $attributes)
    {
        if (!isset($attributes['additional_id'])) {
            return null;
        }

        return $attributes['additional_id'];
    }
}