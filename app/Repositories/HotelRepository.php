<?php

namespace App\Repositories;

use App\Enums\OfferType;
use App\Models\Hotel;
use App\Models\Offer;
use App\Models\Provider;
use App\Repositories\Traits\HasAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


class HotelRepository extends Repository
{
    use HasAddress;

    private $_original_attributes;

    private $provider_id_filter = null;
    private $offer_type_filter  = null;

    public function __construct(Hotel $model)
    {
        $this->model = $model;
    }


    /**
     * Set provider to the instance
     *
     * @param   Provider|int  $provider
     *
     * @return  HotelRepository
     */
    public function setProvider($provider): HotelRepository
    {
        if ($provider instanceof Provider) {
            $this->provider = $provider;
        } else {
            $this->provider = $this->find($provider);
        }

        return $this;
    }


    /**
     * Set status to the instance
     *
     * @param   string  $status
     *
     * @return  HotelRepository
     */
    public function setStatus($status): HotelRepository
    {
        $this->status = $this->find($status);
        return $this;
    }

    /**
     * [createFromOffer description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  [type]         [return description]
     */
    public function createFromOffer(Offer $offer)
    {
        $hotel = $this->store([
            'offer_id' => $offer->id,
        ]);

        return $hotel;
    }
    

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $this->_original_attributes = $attributes;
        if (isset($attributes['address'])) {
            $attributes = array_merge($attributes, $attributes['address']);
        }
        
        if (isset($attributes['hotel'])) {
            $attributes = array_merge($attributes, $attributes['hotel']);
        }

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $attributes = $this->_original_attributes;

        if (isset($attributes['address'])) {
            $this->handleAddress($resource, $attributes['address']);
        }

        $resource = $this->handleExtraAttachments($resource, $attributes);

        return $resource->refresh();
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        $this->_original_attributes = $attributes;

        if (isset($attributes['address'])) {
            $attributes = array_merge($attributes, $attributes['address']);
        }
        
        if (isset($attributes['hotel'])) {
            $attributes = array_merge($attributes, $attributes['hotel']);
        }

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $attributes = $this->_original_attributes;

        $this->handleAddress($resource, $attributes['address']);

        $resource = $this->handleExtraAttachments($resource, $attributes);

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
        if (!empty($attributes['hotel_structures'])) {
            $resource->structures()->sync($attributes['hotel_structures']);
        } else {
            $resource->structures()->sync(null);
        }
        /*
        if (!empty($attributes['observations'])) {
            $resource->observations()->sync($attributes['observations']);
        } else {
            $resource->observations()->sync(null);
        }*/

        return $resource;
    }

    /**
     * Get Hotels by provider
     *
     * @param   array  $attributes
     *
     * @return  [type]         [return description]
     */
    public function getHotelsByProvider(array $providers)
    {
        $this->provider_id_filter = $providers;
        return $this;
    }
    
    /**
     * Get Hotels by registry type Longtrip
     *
     * @return  [type]         [return description]
     */
    public function getOfferLongtripType()
    {
        $this->offer_type_filter = OfferType::LONGTRIP;
        return $this;
    }

    /**
     * List filtred if a type is defined
     *
     * @param   int|null         $paginate
     *
     * @return  Collection
     */
    public function list(?int $paginate = null, array $_params = null): Collection
    {
        $list = parent::list($paginate);

        if (!empty($this->provider_id_filter)) {
            $providers = $this->provider_id_filter;
            $list = $list->whereIn("provider_id", $providers);
        }

        if (!empty($this->offer_type_filter)) {
            $list = $list->where("registry_type", $this->offer_type_filter);;
        }

        return $list;
    }
    
}