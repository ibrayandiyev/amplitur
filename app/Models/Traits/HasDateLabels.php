<?php

namespace App\Models\Traits;

trait HasDateLabels
{

    /**
     * Get formatted created_at label
     * 
     * @return string
     */
    public function getCreatedAtLabelAttribute(): ?string
    {
        if (!$this->created_at) {
            return '';
        }

        return $this->created_at->format('d/m/Y');
    }

    /**
     * Get formatted updated_at label
     * 
     * @return string
     */
    public function getUpdatedAtLabelAttribute(): ?string
    {
        if (!$this->updated_at) {
            return '';
        }

        return $this->updated_at->format('d/m/Y');
    }

    /**
     * Get formatted created_at time label
     *
     * @return  string
     */
    public function getCreatedAtTimeLabelAttribute(): ?string
    {
        if (!$this->created_at) {
            return '';
        }

        return $this->created_at->format('H:i');
    }

    /**
     * Get formatted updated_at time label
     *
     * @return  string
     */
    public function getUpdatedAtTimeLabelAttribute(): ?string
    {
        if (!$this->updated_at) {
            return '';
        }

        return $this->updated_at->format('H:i');
    }

    /**
     * Get formatted starts_at has datetime-local for html input
     *
     * @return  string
     */
    public function getStartsAtLocalAttribute(): ?string
    {
        if (!$this->starts_at) {
            return '';
        }

        return $this->starts_at->format('d/m/Y, H:i');
    }

    /**
     * Get formatted checkin has datetime-local for html input
     *
     * @return  string
     */
    public function getCheckinLocalAttribute(): ?string
    {
        if (!$this->checkin) {
            return '';
        }

        return $this->checkin->format('d/m/Y, H:i');
    }

    /**
     * Get formatted checkout has datetime-local for html input
     *
     * @return  string
     */
    public function getCheckoutLocalAttribute(): ?string
    {
        if (!$this->checkout) {
            return '';
        }

        return $this->checkout->format('d/m/Y, H:i');
    }

    /**
     * Get formatted checkin as datetime
     *
     * @return  string
     */
    public function getCheckinLabelAttribute(): ?string
    {
        if (!$this->checkin) {
            return '';
        }

        return $this->checkin->format('d/m/Y');
    }

    /**
     * Get formatted checkout has datetime-local for html input
     *
     * @return  string
     */
    public function getCheckoutLabelAttribute(): ?string
    {
        if (!$this->checkout) {
            return '';
        }

        return $this->checkout->format('d/m/Y');
    }

    /**
     * Get formatted created_at has datetime-local for html input
     *
     * @return  string
     */
    public function getCreatedAtLocalAttribute(): ?string
    {
        if (!$this->created_at) {
            return '';
        }

        return $this->created_at->format('d/m/Y, H:i');
    }
    /**
     * 
     * Get formatted starts_at has datetime-local for html input
     *
     * @return  string
     */
    public function getStartsAtLabelAttribute(): ?string
    {
        if (!$this->starts_at) {
            return '';
        }

        return $this->starts_at->format('d/m/Y');
    }

    /**
     * Get formatted expires_at has datetime-local for html input
     *
     * @return  string
     */
    public function getExpiresAtLocalAttribute(): ?string
    {
        if (!$this->expires_at) {
            return '';
        }

        return $this->expires_at->format('d/m/Y, H:i');
    }

    /*
     * Get formatted expires_at date label
     *
     * @return  string
     */
    public function getExpiresAtLabelAttribute(): ?string
    {
        if (!$this->expires_at) {
            return '';
        }

        return $this->expires_at->format('d/m/Y');
    }

    /*
     * Get formatted expires_at date label
     *
     * @return  string
     */
    public function getExpiresAtDateAttribute(): ?string
    {
        if (!$this->expires_at) {
            return '';
        }

        return $this->expires_at->format('Y-m-d');
    }

    public function getReleasedAtDateAttribute(): ?string
    {
        if (!$this->released_at) {
            return '';
        }

        return $this->released_at->format('Y-m-d');
    }

    public function getReleasedAtLabelAttribute(): ?string
    {
        if (!$this->released_at) {
            return '';
        }

        return $this->released_at->format('d/m/Y');
    }

    /**
     * Get formatted expired_at has datetime-local for html input
     *
     * @return  string
     */
    public function getExpiredAtLocalAttribute(): ?string
    {
        if (!$this->expired_at) {
            return '';
        }

        return $this->expired_at->format('d/m/Y, H:i');
    }

    /*
     * Get formatted expired_at date label
     *
     * @return  string
     */
    public function getExpiredAtLabelAttribute(): ?string
    {
        if (!$this->expired_at) {
            return '';
        }

        return $this->expired_at->format('d/m/Y');
    }

    /*
     * Get formatted expired_at date label
     *
     * @return  string
     */
    public function getExpiredAtDateAttribute(): ?string
    {
        if (!$this->expired_at) {
            return '';
        }

        return $this->expired_at->format('Y-m-d');
    }

    /*
     * Get formatted boarding_at date label
     *
     * @return  string
     */
    public function getBoardingAtLabelAttribute(): ?string
    {
        if (!$this->boarding_at) {
            return '';
        }

        return $this->boarding_at->format('d/m/Y');
    }

    /*
     * Get formatted boarding_at time label
     *
     * @return  string
     */
    public function getBoardingAtTimeLabelAttribute(): ?string
    {
        if (!$this->boarding_at) {
            return '';
        }

        return $this->boarding_at->format('H:i');
    }

    /*
     * Get formatted ends time label
     *
     * @return  string
     */
    public function getEndsAtTimeLabelAttribute(): ?string
    {
        if (!$this->ends_at) {
            return '';
        }

        return $this->ends_at->format('H:i');
    }

    /*
     * Get formatted ends_at date label
     *
     * @return  string
     */
    public function getEndsAtLabelAttribute(): ?string
    {
        if (!$this->ends_at) {
            return '';
        }

        return $this->ends_at->format('d/m/Y');
    }

    /**
     * Get formatted boarding_at has datetime-local for html input
     *
     * @return  string
     */
    public function getBoardingAtLocalAttribute(): ?string
    {
        if (!$this->boarding_at) {
            return '';
        }

        return $this->boarding_at->format('d/m/Y, H:i');
    }

    /**
     * Get formatted starts_at has datetime-local for html input
     *
     * @return  string
     */
    public function getEndsAtLocalAttribute(): ?string
    {
        if (!$this->ends_at) {
            return '';
        }

        return $this->ends_at->format('d/m/Y, H:i');
    }
}