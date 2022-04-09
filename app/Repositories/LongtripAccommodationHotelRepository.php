<?php

namespace App\Repositories;

use App\Enums\OfferType;
use App\Models\LongtripAccommodation;
use App\Models\LongtripAccommodationHotel;
use App\Models\LongtripAccommodationsPricing;
use App\Models\LongtripRoute;
use App\Models\Offer;
use App\Repositories\Traits\HasImageUpload;
use Illuminate\Database\Eloquent\Model;

class LongtripAccommodationHotelRepository extends Repository
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

    public function __construct(LongtripAccommodationHotel $model)
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
    public function setOffer(Offer $offer): LongtripAccommodationHotelRepository
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
    public function setLongtripRoute(LongtripRoute $longtripRoute): LongtripAccommodationHotelRepository
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

        if(isset($attributes['hotel_id']) && $attributes['hotel_id'] == ""){
            $attributes['hotel']['registry_type'] = OfferType::LONGTRIP;
            $entityHotel = app(HotelRepository::class)->store($attributes);
            $attributes['hotel_id'] = $entityHotel->id;
        }
        unset($attributes['hotel']);
        unset($attributes['address']);
        
        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
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
        return $resource;
    }

    /**
     * @inherited
     */
    public function updateQuickAttributes(array $attributes): bool
    {
        if(!isset($attributes["quick"])){
            return false;
        }
        foreach($attributes["quick"] as $_data){
            $entity     = app(LongtripAccommodationHotel::class)->find($_data['id']);
            if (isset($_data['checkin'])) {
                $_data['checkin'] = convertDate($_data['checkin']);
            }
            if (isset($_data['checkout'])) {
                $_data['checkout'] = convertDate($_data['checkout']);
            }
            $this->update($entity, $_data);
        }
        return true;
    }

    
}