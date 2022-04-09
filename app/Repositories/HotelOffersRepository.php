<?php

namespace App\Repositories;

use App\Enums\OfferType;
use App\Models\HotelOffers;
use App\Models\Offer;
use App\Repositories\Traits\HasAddress;
use Illuminate\Database\Eloquent\Model;

class HotelOffersRepository extends Repository
{
    use HasAddress;
    private $hotelRepository;

    public function __construct(HotelOffers $model, HotelRepository $hotelRepository)
    {
        $this->model = $model;
        $this->hotelRepository = $hotelRepository;
    }

    /**
     * [createFromOffer description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  [type]         [return description]
     */
    public function createFromOffer(Offer $offer, $attributes)
    {
        $hotel              = $this->hotelRepository->store([
            'registry_type' => OfferType::HOTEL,
            'provider_id'   => $offer->provider_id]);
        $minimumStay    = isset($attributes["hotel_offer"]["minimum_stay"])?$attributes["hotel_offer"]["minimum_stay"]:0;
        $_hotelOffer = [
            'offer_id'      => $offer->id,
            'hotel_id'      => $hotel->id,
            "minimum_stay"  => $minimumStay
        ];
        $hotelOffer         = $this->store($_hotelOffer);

        return $hotelOffer;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $resource = $this->handleExtraAttachments($resource, $attributes);

        return $resource->refresh();
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
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

        if (!empty($attributes['observations'])) {
            $resource->observations()->sync($attributes['observations']);
        } else {
            $resource->observations()->sync(null);
        }

        return $resource;
    }
}