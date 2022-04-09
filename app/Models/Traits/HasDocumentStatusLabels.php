<?php

namespace App\Models\Traits;

use App\Enums\DocumentStatus;

trait HasDocumentStatusLabels
{
    /**
     * Get formatted created_at label
     * 
     * @return string
     */
    public function getStatusLabelAttribute(): ?string
    {
        if ($this->isInAnalysis()) {
            return '<span class="label label-warning">' . __('resources.process-statues.in-analysis') . '</span>';
        } else if ($this->isApproved()) {
            return '<span class="label label-success">' . __('resources.process-statues.active') . '</span>';
        } else if ($this->isDeclined()) {
            return '<span class="label label-danger">' . __('resources.process-statues.refused') . '</span>';
        } else {
            return '<span class="label">'. $this->status .'</span>';
        }
    }

    public function isInAnalysis(): bool
    {
        return $this->status == DocumentStatus::IN_ANALYSIS;
    }

    public function isApproved(): bool
    {
        return $this->status == DocumentStatus::APPROVED;
    }

    public function isDeclined(): bool
    {
        return $this->status == DocumentStatus::DECLINED;
    }
}