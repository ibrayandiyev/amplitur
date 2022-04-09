<?php

namespace App\Models\Relationships;

use App\Enums\ProcessStatus;
use App\Models\Package;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPackages
{
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    /**
     * Get all packages with active and in-analysis statues
     *
     * @return  HasMany
     */
    public function availablePackages(): HasMany
    {
        return $this->packages()
            ->whereIn('status', [
                ProcessStatus::ACTIVE,
                ProcessStatus::IN_ANALYSIS,
            ]);
    }

    /**
     * Get all packages with active status
     *
     * @return  HasMany
     */
    public function activePackages(): HasMany
    {
        return $this->packages()->where('status', ProcessStatus::ACTIVE);
    }

    /**
     * Check if relationed resource has packages
     *
     * @return  bool
     */
    public function hasPackages(): bool
    {
        return $this->packages()->count() > 0;
    }

    /**
     * Check if relationed resource has available packages
     *
     * @return  bool    [return description]
     */
    public function hasAvailablePackages(): bool
    {
        return $this->availablePackages()->count() > 0;
    }

    /**
     * Check if relationed resource has active packages
     *
     * @return  bool
     */
    public function hasActivePackages(): bool
    {
        return $this->activePackages()->count() > 0;
    }
}
