<?php

namespace App\Models\Traits;

use App\Enums\ProcessStatus;

trait HasProcessStatusLabels
{
    /**
     * Get formatted created_at label
     *
     * @return string
     */
    public function getStatusLabelAttribute(): ?string
    {
        if ($this->status == ProcessStatus::IN_ANALYSIS) {
            return '<span class="label label-warning">' . __('resources.process-statues.in-analysis') . '</span>';
        } else if ($this->status == ProcessStatus::PENDING) {
            return '<span class="label label-warning">' . __('resources.process-statues.pending') . '</span>';
        } else if ($this->status == ProcessStatus::ACTIVE) {
            return '<span class="label label-success">' . __('resources.process-statues.active') . '</span>';
        } else if ($this->status == ProcessStatus::CONFIRMED) {
            return '<span class="label label-success">' . __('resources.process-statues.confirmed') . '</span>';
        } else if ($this->status == ProcessStatus::PAID) {
            return '<span class="label label-success">' . __('resources.process-statues.paid') . '</span>';
        } else if ($this->status == ProcessStatus::SUSPENDED) {
            return '<span class="label label-inverse">' . __('resources.process-statues.suspended') . '</span>';
        } else if ($this->status == ProcessStatus::REFUSED) {
            return '<span class="label label-danger">' . __('resources.process-statues.refused') . '</span>';
        } else if ($this->status == ProcessStatus::CANCELED) {
            return '<span class="label label-danger">' . __('resources.process-statues.canceled') . '</span>';
        } else if ($this->status == ProcessStatus::INACTIVE) {
            return '<span class="label label-info">' . __('resources.process-statues.inactive') . '</span>';
        } else if ($this->status == ProcessStatus::REFUNDED) {
            return '<span class="label label-inverse">' . __('resources.process-statues.refunded') . '</span>';
        } else if ($this->status == ProcessStatus::BLOCKED) {
            return '<span class="label label-inverse">' . __('resources.process-statues.blocked') . '</span>';
        } else {
            return '<span class="label">'. $this->status .'</span>';
        }
    }

    /**
     * Get formatted created_at label
     *
     * @return string
     */
    public function getPaymentStatusLabelAttribute(): ?string
    {
        if ($this->payment_status == ProcessStatus::IN_ANALYSIS) {
            return '<span class="label label-warning">' . __('resources.process-statues.in-analysis') . '</span>';
        } else if ($this->payment_status == ProcessStatus::PENDING) {
            return '<span class="label label-warning">' . __('resources.process-statues.pending') . '</span>';
        } else if ($this->payment_status == ProcessStatus::PENDING_CONFIRMATION) {
            return '<span class="label label-warning">' . __('resources.process-statues.pending') . '</span>';
        } else if ($this->payment_status == ProcessStatus::ACTIVE) {
            return '<span class="label label-success">' . __('resources.process-statues.active') . '</span>';
        } else if ($this->payment_status == ProcessStatus::CONFIRMED) {
            return '<span class="label label-success">' . __('resources.process-statues.confirmed') . '</span>';
        } else if ($this->payment_status == ProcessStatus::PAID) {
            return '<span class="label label-success">' . __('resources.process-statues.paid') . '</span>';
        } else if ($this->payment_status == ProcessStatus::SUSPENDED) {
            return '<span class="label label-inverse">' . __('resources.process-statues.suspended') . '</span>';
        } else if ($this->payment_status == ProcessStatus::REFUSED) {
            return '<span class="label label-danger">' . __('resources.process-statues.refused') . '</span>';
        } else if ($this->payment_status == ProcessStatus::CANCELED) {
            return '<span class="label label-danger">' . __('resources.process-statues.canceled') . '</span>';
        } else if ($this->payment_status == ProcessStatus::INACTIVE) {
            return '<span class="label label-info">' . __('resources.process-statues.inactive') . '</span>';
        } else if ($this->payment_status == ProcessStatus::REFUNDED) {
            return '<span class="label label-inverse">' . __('resources.process-statues.refunded') . '</span>';
        } else if ($this->payment_status == ProcessStatus::PARTIAL_PAID) {
            return '<span class="label label-warning">' . __('resources.process-statues.partial_paid') . '</span>';
        } else {
            return '<span class="label">'. $this->payment_status .'</span>';
        }
    }

    /**
     * Get formatted created_at label
     *
     * @return string
     */
    public function getDocumentStatusLabelAttribute(): ?string
    {
        if ($this->document_status == ProcessStatus::IN_ANALYSIS) {
            return '<span class="label label-warning">' . __('resources.process-statues.in-analysis') . '</span>';
        } else if ($this->document_status == ProcessStatus::PENDING) {
            return '<span class="label label-warning">' . __('resources.process-statues.pending') . '</span>';
        } else if ($this->document_status == ProcessStatus::ACTIVE) {
            return '<span class="label label-success">' . __('resources.process-statues.active') . '</span>';
        } else if ($this->document_status == ProcessStatus::CONFIRMED) {
            return '<span class="label label-success">' . __('resources.process-statues.confirmed') . '</span>';
        } else if ($this->document_status == ProcessStatus::PAID) {
            return '<span class="label label-success">' . __('resources.process-statues.paid') . '</span>';
        } else if ($this->document_status == ProcessStatus::SUSPENDED) {
            return '<span class="label label-inverse">' . __('resources.process-statues.suspended') . '</span>';
        } else if ($this->document_status == ProcessStatus::REFUSED) {
            return '<span class="label label-danger">' . __('resources.process-statues.refused') . '</span>';
        } else if ($this->document_status == ProcessStatus::CANCELED) {
            return '<span class="label label-danger">' . __('resources.process-statues.canceled') . '</span>';
        } else if ($this->document_status == ProcessStatus::INACTIVE) {
            return '<span class="label label-info">' . __('resources.process-statues.inactive') . '</span>';
        } else if ($this->document_status == ProcessStatus::REFUNDED) {
            return '<span class="label label-inverse">' . __('resources.process-statues.refunded') . '</span>';
        } else if ($this->document_status == ProcessStatus::PARTIAL_RECEIVED) {
            return '<span class="label label-warning">' . __('resources.process-statues.partial_received') . '</span>';
        } else {
            return '<span class="label">'. $this->document_status .'</span>';
        }
    }

    /**
     * Get formatted created_at label
     *
     * @return string
     */
    public function getVoucherStatusLabelAttribute(): ?string
    {
        if ($this->voucher_status == ProcessStatus::IN_ANALYSIS) {
            return '<span class="label label-warning">' . __('resources.process-statues.in-analysis') . '</span>';
        } else if ($this->voucher_status == ProcessStatus::PENDING) {
            return '<span class="label label-warning">' . __('resources.process-statues.pending') . '</span>';
        } else if ($this->voucher_status == ProcessStatus::ACTIVE) {
            return '<span class="label label-success">' . __('resources.process-statues.active') . '</span>';
        } else if ($this->voucher_status == ProcessStatus::CONFIRMED) {
            return '<span class="label label-success">' . __('resources.process-statues.confirmed') . '</span>';
        } else if ($this->voucher_status == ProcessStatus::PAID) {
            return '<span class="label label-success">' . __('resources.process-statues.paid') . '</span>';
        } else if ($this->voucher_status == ProcessStatus::SUSPENDED) {
            return '<span class="label label-inverse">' . __('resources.process-statues.suspended') . '</span>';
        } else if ($this->voucher_status == ProcessStatus::REFUSED) {
            return '<span class="label label-danger">' . __('resources.process-statues.refused') . '</span>';
        } else if ($this->voucher_status == ProcessStatus::CANCELED) {
            return '<span class="label label-danger">' . __('resources.process-statues.canceled') . '</span>';
        } else if ($this->voucher_status == ProcessStatus::INACTIVE) {
            return '<span class="label label-info">' . __('resources.process-statues.inactive') . '</span>';
        } else if ($this->voucher_status == ProcessStatus::REFUNDED) {
            return '<span class="label label-inverse">' . __('resources.process-statues.refunded') . '</span>';
        } else if ($this->voucher_status == ProcessStatus::RELEASED) {
            return '<span class="label label-inverse">' . __('resources.process-statues.released') . '</span>';
        } else {
            return '<span class="label">'. $this->voucher_status .'</span>';
        }
    }
}
