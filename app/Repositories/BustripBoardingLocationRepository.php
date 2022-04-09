<?php

namespace App\Repositories;

use App\Models\BustripBoardingLocation;
use App\Models\BustripRoute;
use App\Repositories\Traits\HasAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BustripBoardingLocationRepository extends Repository
{
    use HasAddress;

    /**
     * @var BustripRoute
     */
    public $bustripRoute;

    public function __construct(BustripBoardingLocation $model)
    {
        $this->model = $model;
    }

    /**
     * Set repository BustripRoute relation
     *
     * @param   BustripRoute           $bustripRoute
     *
     * @return  CompanyRepository
     */
    public function setBustripRoute(BustripRoute $bustripRoute): BustripBoardingLocationRepository
    {
        $this->bustripRoute = $bustripRoute;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['bustrip_route_id'] = $this->bustripRoute->id;
        $attributes['price'] = sanitizeMoney($attributes['price']);

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        $attributes['price'] = sanitizeMoney($attributes['price']);

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $this->handleAddress($resource, $attributes['address']);

        $resource = $this->handleExtraAttachments($resource, $attributes);

        return $resource->refresh();
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {

        $canChange = !(user()->isProvider() && $resource->bustripRoute->offer->hasBookings());
        if ($canChange) {
            $this->handleAddress($resource, $attributes['address']);

            $resource = $this->handleExtraAttachments($resource, $attributes);
        }

        return $resource->refresh();
    }

    /**
     * Sync additionals
     *
     * @param   Model  $resource
     * @param   array  $attributes
     *
     * @return  Model
     */
    public function handleExtraAttachments(Model $resource, array $attributes): Model
    {
        if (!empty($attributes['additionals'])) {
            $resource->additionals()->sync($attributes['additionals']);
        } else {
            $resource->additionals()->sync(null);
        }

        return $resource;
    }

    /**
     * Get boarding location additionals
     *
     * @param   BustripBoardingLocation|Collection $boardingLocations
     *
     * @return  Collection
     */
    public function getAdditionals($boardingLocations): Collection
    {
        $additionals = collect();

        if (is_iterable($boardingLocations)) {
            foreach ($boardingLocations as $boardingLocation) {
                $additionals->push($boardingLocation->additionals);
            }

            return $additionals->flatten(1);
        }

        $additionals = $boardingLocations->additionals->toCollection();

        return $additionals;
    }
}