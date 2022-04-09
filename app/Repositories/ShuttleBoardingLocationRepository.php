<?php

namespace App\Repositories;

use App\Models\ShuttleBoardingLocation;
use App\Models\ShuttleRoute;
use App\Repositories\Traits\HasAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ShuttleBoardingLocationRepository extends Repository
{
    use HasAddress;

    /**
     * @var ShuttleRoute
     */
    public $shuttleRoute;

    public function __construct(ShuttleBoardingLocation $model)
    {
        $this->model = $model;
    }

    /**
     * Set repository ShuttleRoute relation
     *
     * @param   ShuttleRoute           $shuttleRoute
     *
     * @return  CompanyRepository
     */
    public function setShuttleRoute(ShuttleRoute $shuttleRoute): ShuttleBoardingLocationRepository
    {
        $this->shuttleRoute = $shuttleRoute;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['shuttle_route_id'] = $this->shuttleRoute->id;
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

        $canChange = !(user()->isProvider() && $resource->ShuttleRoute->offer->hasBookings());
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
     * @param   ShuttleBoardingLocation|Collection $boardingLocations
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
