<?php

namespace App\Models\Traits;

trait HasIsActiveLabels
{
    /**
     * Get formatted created_at label
     *
     * @return string
     */
    public function getIsActiveLabelAttribute(): ?string
    {
        if ($this->is_active == 1) {
            return '<span class="label label-success">' . __('resources.process-statues.active') . '</span>';
        } else {
            return '<span class="label label-info">' . __('resources.process-statues.inactive') . '</span>';
        } 
    }
    
}
