<?php

namespace App\Repositories;

use App\Models\LongtripBoardingLocation;
use App\Models\LongtripRoute;
use App\Repositories\Traits\HasAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class LongtripBoardingLocationRepository extends Repository
{
    use HasAddress;

    /**
     * @var LongtripRoute
     */
    public $longtripRoute;

    public function __construct(LongtripBoardingLocation $model)
    {
        $this->model = $model;
    }

    /**
     * Set repository LongtripRoute relation
     *
     * @param   LongtripRoute           $longtripRoute
     *
     * @return  CompanyRepository
     */
    public function setLongtripRoute(LongtripRoute $longtripRoute): LongtripBoardingLocationRepository
    {
        $this->longtripRoute = $longtripRoute;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['longtrip_route_id'] = $this->longtripRoute->id;
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

        return $resource->refresh();
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $canChange = !(user()->isProvider() && $resource->longtripRoute->offer->hasBookings());
        if ($canChange) {
            $this->handleAddress($resource, $attributes['address']);
        }

        return $resource->refresh();
    }
}