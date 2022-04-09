<?php

namespace App\Repositories;

use App\Models\LongtripAccommodationsPricing;
use App\Models\Offer;
use Illuminate\Support\Collection;

class LongtripAccommodationsPricingRepository extends Repository
{
    public function __construct(LongtripAccommodationsPricing $model)
    {
        $this->model = $model;
    }

    public function sync(Offer $offer, array $pricings): Collection
    {
        $longtripAccommodationsPrincings = collect();

        foreach ($pricings as $attributes) {

            if (isset($attributes['price'])) {
                if($attributes['price'] == ""){
                    $attributes['price'] = 0;
                }
                $attributes['price'] = sanitizeMoney($attributes['price']);
            }

            if (isset($attributes['id'])) {
                $longtripAccommodationsPrincing = $this->find($attributes['id']);
                $longtripAccommodationsPrincing = $this->update($longtripAccommodationsPrincing, $attributes);
                $longtripAccommodationsPrincings->push($longtripAccommodationsPrincing);
            } else {
                $attributes['offer_id'] = $offer->id;
                $longtripAccommodationsPrincing = $this->store($attributes);
                $longtripAccommodationsPrincings->push($longtripAccommodationsPrincing);
            }
        }

        return $longtripAccommodationsPrincings;
    }

    public function deleteRelation($longtrip_route_id=null, $longtrip_accommodation_type_id=null){
        $entities = $this->where('longtrip_route_id', $longtrip_route_id)
            ->where('longtrip_accommodation_type_id', $longtrip_accommodation_type_id)
            ->list();
        if($entities){
            foreach($entities as $e){
                $e->delete();
            }
        }
    }
}
