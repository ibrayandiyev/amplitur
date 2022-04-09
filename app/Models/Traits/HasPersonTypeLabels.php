<?php

namespace App\Models\Traits;

use App\Enums\PersonType;

trait HasPersonTypeLabels
{

    /**
     * Get formatted person type label
     *
     * @return string
     */
    public function getTypeLabelAttribute(): ?string
    {
        if ($this->type == PersonType::LEGAL) {
            return '<span class="label label-light-success">'. __('messages.person-legal') .'</span>';
        } else {
            return '<span class="label label-light-warning">' . __('messages.person-fisical') . '</span>';
        }
    }
}

