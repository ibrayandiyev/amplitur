<?php

namespace App\Repositories;

use App\Models\LongtripAccommodation;
use App\Models\LongtripAccommodationsPricing;
use App\Models\LongtripRoute;
use App\Models\Offer;
use App\Repositories\Traits\HasImageUpload;
use Illuminate\Database\Eloquent\Model;

class LongtripAccommodationRepository extends Repository
{
    use HasImageUpload;

    /**
     * @var Offer
     */
    protected $offer;

    /**
     * @var LongtripRoute
     */
    protected $longtripRoute;

    public function __construct(LongtripAccommodation $model)
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
    public function setOffer(Offer $offer): LongtripAccommodationRepository
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Set repository LongtripRoute relation
     *
     * @param   LongtripRoute                    $longtripRoute
     *
     * @return  LongtripAccommodationRepository
     */
    public function setLongtripRoute(LongtripRoute $longtripRoute): LongtripAccommodationRepository
    {
        $this->longtripRoute = $longtripRoute;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['offer_id'] = $this->offer->id;
        $attributes['longtrip_route_id'] = $this->longtripRoute->id;

        if (isset($attributes['address'])) {
            $attributes = array_merge($attributes, $attributes['address']);
        }
        
        if (isset($attributes['price'])) {
            $attributes['price'] = sanitizeMoney($attributes['price']);
        }

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        if ($resource->longtripRoute->hasPricing()) {
            $pricing = $resource->longtripRoute->longtripAccommodationsPricings()
                ->where('longtrip_route_id', $resource->longtrip_route_id)
                ->where('longtrip_accommodation_type_id', $resource->longtrip_accommodation_type_id)
                ->get();
                if($pricing->count() == 0){
                    app(LongtripAccommodationsPricingRepository::class)->store([
                        'offer_id' => $resource->offer_id,
                        'longtrip_route_id' => $resource->longtrip_route_id,
                        'longtrip_accommodation_type_id' => $resource->longtrip_accommodation_type_id,
                        'price' => 0,
                        'stock' => 0,
                    ]);
                }
                
        }

        return $resource;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        if (isset($attributes['price'])) {
            $attributes['price'] = sanitizeMoney($attributes['price']);
        }

        if (isset($attributes['address'])) {
            $attributes = array_merge($attributes, $attributes['address']);
        }

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        if ($resource->longtripRoute->hasPricing()) {
            $pricing = app(LongtripAccommodationsPricing::class)
                ->where('longtrip_accommodation_type_id', $resource->longtrip_accommodation_type_id)
                ->where('offer_id', $resource->offer_id)
                ->where('longtrip_route_id', $resource->longtrip_route_id)
                ->first();

            if (empty($pricing)) {
                app(LongtripAccommodationsPricingRepository::class)->store([
                    'offer_id' => $resource->offer_id,
                    'longtrip_route_id' => $resource->longtrip_route_id,
                    'longtrip_accommodation_type_id' => $resource->longtrip_accommodation_type_id,
                    'price' => 0,
                    'stock' => 0,
                ]);
            } else {
                app(LongtripAccommodationsPricingRepository::class)->update($pricing, [
                    'longtrip_accommodation_type_id' => $resource->longtrip_accommodation_type_id,
                ]);
            }
        }

        return $resource;
    }

    public function getLongtripAccommodationByRouteAccommodationType($longtripRouteId=0, $longtripAccommodationTypeId=0){
        return $this->model->where("longtrip_route_id", $longtripRouteId)->where("longtrip_accommodation_type_id", $longtripAccommodationTypeId)->first();
    }
}