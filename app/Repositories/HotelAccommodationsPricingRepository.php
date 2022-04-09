<?php

namespace App\Repositories;

use App\Models\HotelAccommodationsPricing;
use App\Models\Offer;
use Illuminate\Support\Collection;

class HotelAccommodationsPricingRepository extends Repository
{
    public function __construct(HotelAccommodationsPricing $model)
    {
        $this->model = $model;
    }

    public function sync(Offer $offer, array $pricings): Collection
    {
        $hotelAccommodationPricings = collect();

        foreach ($pricings as $hotelAccommodationId => $acommodationPricings) {
            foreach ($acommodationPricings as $pricing) {

                $hotelAccommodationPricing = $this->model
                    ->where('offer_id', $offer->id)
                    ->where('hotel_accommodation_id', $hotelAccommodationId)
                    ->where('checkin', $pricing['checkin'])
                    ->where('checkout', $pricing['checkout'])
                    ->first();

                if ($hotelAccommodationPricing) {
                    $hotelAccommodationPricings->push(
                        $this->update($hotelAccommodationPricing, [
                            'checkin' => $pricing['checkin'],
                            'checkout' => $pricing['checkout'],
                            'price' => sanitizeMoney($pricing['price']),
                            'stock' => $pricing['stock'],
                            'required_overnight' => $pricing['required_overnight'],
                        ])
                    ); 
                } else {
                    $hotelAccommodationPricings->push(
                        $this->store([
                            'offer_id' => $offer->id,
                            'hotel_accommodation_id' => $hotelAccommodationId,
                            'checkin' => $pricing['checkin'],
                            'checkout' => $pricing['checkout'],
                            'price' => sanitizeMoney($pricing['price']),
                            'stock' => $pricing['stock'],
                            'required_overnight' => $pricing['required_overnight'],
                        ])
                    ); 
                }
            }
        }

        return $hotelAccommodationPricings;
    }
}