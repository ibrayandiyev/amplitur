<?php

namespace App\Repositories;

use App\Models\BustripRoute;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Model;

class BustripRouteRepository extends Repository
{
    /**
     * @var Offer
     */
    public $offer;

    public function __construct(BustripRoute $model)
    {
        $this->model = $model;
    }

    /**
     * Set repository Offer relation
     *
     * @param   Offer           $offer
     *
     * @return  CompanyRepository
     */
    public function setOffer(Offer $offer): BustripRouteRepository
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['offer_id'] = $this->offer->id;

        $attributes['fields']['sale_dates'] = $attributes['fields']['sale_dates'] ?? null;

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        if (!(user()->isProvider() && $resource->offer->hasBookings())) {
            $attributes['fields']['sale_dates'] = $attributes['fields']['sale_dates'] ?? null;
        }

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        if (!(user()->isProvider() && $resource->offer->hasBookings())) {
            $resource = $this->handleExtraAttachments($resource, $attributes);
        }
        
        return $resource->refresh();
    }

    /**
     * Sync inclusions and exclusions
     *
     * @param   Model  $resource
     * @param   array  $attributes
     *
     * @return  Model
     */
    public function handleExtraAttachments(Model $resource, array $attributes): Model
    {
        if (!empty($attributes['inclusions'])) {
            $resource->inclusions()->sync($attributes['inclusions']);
        } else {
            $resource->inclusions()->sync(null);
        }

        if (!empty($attributes['exclusions'])) {
            $resource->exclusions()->sync($attributes['exclusions']);
        } else {
            $resource->exclusions()->sync(null);
        }

        if (!empty($attributes['observations'])) {
            $resource->observations()->sync($attributes['observations']);
        } else {
            $resource->observations()->sync(null);
        }

        return $resource;
    }
}