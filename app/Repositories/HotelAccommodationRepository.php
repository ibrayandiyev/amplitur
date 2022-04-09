<?php

namespace App\Repositories;

use App\Models\HotelAccommodation;
use App\Models\Offer;
use App\Repositories\Traits\HasAddress;
use App\Repositories\Traits\HasImageUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class HotelAccommodationRepository extends Repository
{
    use HasAddress,
        HasImageUpload;

    /**
     * @var Offer
     */
    protected $offer;

    public function __construct(HotelAccommodation $model)
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
    public function setOffer(Offer $offer): HotelAccommodationRepository
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['hotel_offers_id'] = $this->offer->hotelOffer->id;

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $resource = $this->handleExtraAttachments($resource, $attributes);

        $attributes = $this->handleImageUpload($resource, $attributes);

        return $resource->refresh();
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $resource = $this->handleExtraAttachments($resource, $attributes);

        $attributes = $this->handleImageUpload($resource, $attributes);

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
        if (!empty($attributes['hotel_accommodation_structures'])) {
            $resource->structures()->sync($attributes['hotel_accommodation_structures']);
        } else {
            $resource->structures()->sync(null);
        }

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

        return $resource;
    }

    /**
     * Get hotel accommodations additionals
     *
     * @param   HotelAccommodation|Collection $hotelAccommodations
     *
     * @return  Collection
     */
    public function getAdditionals($hotelAccommodations): Collection
    {
        $additionals = collect();

        if (is_iterable($hotelAccommodations)) {
            foreach ($hotelAccommodations as $hotelAccommodation) {
                $additionals->push($hotelAccommodation->additionals);
            }

            return $additionals->flatten(1);
        }

        $additionals = $hotelAccommodations->additionals->toCollection();

        return $additionals;
    }
}