<?php

namespace App\Services;

use App\Enums\Currency;
use App\Models\Additional;
use App\Models\Company;
use App\Models\HotelAccommodationsPricing;
use App\Models\Image;
use App\Models\Observation;
use App\Models\Offer;
use App\Models\Package;
use App\Models\Provider;
use App\Repositories\BustripBoardingLocationRepository;
use App\Repositories\HotelRepository;
use App\Repositories\LongtripBoardingLocationRepository;
use App\Repositories\OfferRepository;
use App\Repositories\ShuttleBoardingLocationRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferReplicationService
{
    /**
     * @var OfferRepository
     */
    protected $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    /**
     * [replicate description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  Offer          [return description]
     */
    public function replicate(Offer $offer, Package $package, Provider $provider, Company $company, Request $request): Offer
    {
        try {
            DB::beginTransaction();

            $newOffer = $offer->replicate();
            $newOffer->package_id = $package->id;
            $newOffer->provider_id = $provider->id;
            $newOffer->company_id = $company->id;
            $newOffer->status = 'in-analysis';
            $newOffer->push();

            $newOffer = $this->replicateRelationships($offer, $newOffer, $request);

            $newOffer->save();

            DB::commit();

            return $newOffer->refresh();
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            DB::rollback();
            throw $ex;
        }
    }

    /**
     * [replicateRelationships description]
     *
     * @param   Offer  $offer     [$offer description]
     * @param   Offer  $newOffer  [$newOffer description]
     *
     * @return  [type]            [return description]
     */
    protected function replicateRelationships(Offer $offer, Offer $newOffer, Request $request): Offer
    {
        if ($newOffer->isLongtrip()) {
            $newOffer = $this->replicateLongtripRelationships($offer, $newOffer, $request);
        } else if ($newOffer->isHotel()) {
            $newOffer = $this->replicateHotelRelatioships($offer, $newOffer);
        } else if ($newOffer->isBustrip()) {
            $newOffer = $this->replicateBustripRelatioships($offer, $newOffer, $request);
        } else if ($newOffer->isShuttle()) {
            $newOffer = $this->replicateShuttleRelatioships($offer, $newOffer, $request);
        } else if ($newOffer->isAdditional()) {
            $newOffer = $this->replicateAdditionalRelatioships($offer, $newOffer, $request);
        }

        return $newOffer->refresh();
    }

    /**
     * [replicateLongtripRelationships description]
     *
     * @param   Offer  $offer     [$offer description]
     * @param   Offer  $newOffer  [$newOffer description]
     *
     * @return  [type]            [return description]
     */
    protected function replicateLongtripRelationships(Offer $offer, Offer $newOffer, Request $request): Offer
    {
        $_request_data = $request->toArray();

        $currency               = $this->replicateCurrency($offer, $newOffer, $_request_data);

        foreach ($offer->longtripRoutes as $longtripRoute) {
            if(!isset($_request_data['route'][$longtripRoute->id])){
                continue;
            }
            $newLongtripRoute = $longtripRoute->replicate();
            $newLongtripRoute->offer_id     = $newOffer->id;
            $newLongtripRoute->fields       = ['sale_dates' => null];
            $newLongtripRoute->push();

            foreach ($longtripRoute->longtripBoardingLocations as $longtripBoardingLocation) {
                if(!isset($_request_data['boarding_at'][$longtripBoardingLocation->id])){
                    continue;
                }
                
                $newLongtripBoardingLocation = $longtripBoardingLocation->replicate();
                $newLongtripBoardingLocation->longtrip_route_id = $newLongtripRoute->id;
                
                if(isset($_request_data['boarding_at'][$longtripBoardingLocation->id])){
                    $boarding_at = convertDatetime($_request_data['boarding_at'][$longtripBoardingLocation->id]);
                    $newLongtripBoardingLocation->boarding_at = $boarding_at;
                }
                if(isset($_request_data['ends_at'][$longtripBoardingLocation->id])){
                    $ends_at = convertDatetime($_request_data['ends_at'][$longtripBoardingLocation->id]);
                    $newLongtripBoardingLocation->ends_at = $ends_at;
                }
                $newLongtripBoardingLocation->push();

                app(LongtripBoardingLocationRepository::class)->handleAddress($newLongtripBoardingLocation, $longtripBoardingLocation->address->toArray());
                
                if (!empty($longtripBoardingLocation->additionals)) {
                    $newLongtripBoardingLocation->additionals()->sync($longtripBoardingLocation->additionals->pluck('id')->toArray());
                }

            }

            foreach ($longtripRoute->longtripAccommodations as $longtripAccommodation) {
                $newLongtripAccommodation = $longtripAccommodation->replicate();
                $newLongtripAccommodation->offer_id = $newOffer->id;
                $newLongtripAccommodation->longtrip_route_id = $newLongtripRoute->id;
                $newLongtripAccommodation->push();
            }

            foreach ($longtripRoute->longtripAccommodationsPricings as $longtripAccommodationsPricing) {
                $newAccommodationsPricing = $longtripAccommodationsPricing->replicate();
                $newAccommodationsPricing->offer_id = $newOffer->id;
                $newAccommodationsPricing->longtrip_route_id = $newLongtripRoute->id;
                $newAccommodationsPricing->push();
            }

            if (!empty($longtripRoute->inclusions)) {
                $newLongtripRoute->inclusions()->sync($longtripRoute->inclusions->pluck('id')->toArray());
            }

            if (!empty($longtripRoute->exclusions)) {
                $newLongtripRoute->exclusions()->sync($longtripRoute->exclusions->pluck('id')->toArray());
            }

            if (!empty($longtripRoute->observations)) {
                $newLongtripRoute->observations()->sync($longtripRoute->observations->pluck('id')->toArray());
            }

            if (!empty($longtripRoute->additionals)) {
                $newLongtripRoute->additionals()->sync($longtripRoute->additionals->pluck('id')->toArray());
            }
        }

        $newOffer->save();

        return $newOffer;
    }

    /**
     * [replicateHotelRelatioships description]
     *
     * @param   Offer  $offer     [$offer description]
     * @param   Offer  $newOffer  [$newOffer description]
     *
     * @return  [type]            [return description]
     */
    protected function replicateHotelRelatioships(Offer $offer, Offer $newOffer): Offer
    {
        $newHotel = $offer->hotelOffer->hotel->replicate();
        //$newHotel->offer_id = $newOffer->id;
        $newHotel->push();
        $newHotelOffer                  = $offer->hotelOffer->replicate();
        $newHotelOffer->offer_id        = $newOffer->id;
        $newHotelOffer->hotel_id        = $newHotel->id;
        $newHotelOffer->minimum_stay    = $offer->hotelOffer->minimum_stay;
        $newHotelOffer->push();

        if($offer->hotelOffer->hotel->address != null){
            $newHotel->address_id = null;
            $newHotel->refresh();
            $_newAddress    = $offer->hotelOffer->hotel->address->toArray();
            app(HotelRepository::class)->handleAddress($newHotel, $_newAddress);
        }
        if($offer->hotelOffer->accommodations){
            foreach ($offer->hotelOffer->accommodations as $accommodation) {
                $newAccommodation = $accommodation->replicate();
                $newAccommodation->hotel_offers_id = $newHotelOffer->id;
                $newAccommodation->push();

                if (!empty($accommodation->structures)) {
                    $newAccommodation->structures()->sync($accommodation->structures->pluck('id')->toArray());
                }

                if (!empty($accommodation->structures)) {
                    $newAccommodation->additionals()->sync($accommodation->additionals->pluck('id')->toArray());
                }

                /*
                    Rule for replication the Hotel Pricing:
                        - We get the initial and end date of the package
                        - Create the dates from 2 days before the initial date until 3 days after the 
                            end date of the package.
                        - Price, stock remains 0.
                */
                $startDate      = Carbon::createFromFormat('Y-m-d H:i:s', $newOffer->package->starts_at);
                $endDate        = Carbon::createFromFormat('Y-m-d H:i:s', $newOffer->package->ends_at);
                $initialDate    = $startDate->subDays(2);
                $finalDate      = $endDate->addDays(4);
                $count          = 0;
                for($i=$initialDate->toDateTimeString();$i<$finalDate->toDateTimeString();$i=$initialDate){
                    $checkin            = $initialDate->format("Y-m-d");
                    $initialDate->addDay();
                    $checkout           = $initialDate->format("Y-m-d");

                    $count++;
                    if($count>30){break;}   // failsafe
                    $_data  = ['hotel_accommodation_id' => $newAccommodation->id,
                        'offer_id'              => $newOffer->id,
                        'price'                 => 0,
                        'stock'                 => 0,
                        'required_overnight'    => 0,
                        "checkin"               => $checkin,
                        "checkout"              => $checkout
                    ];
                    $newHotelAccommodationsPricing = new HotelAccommodationsPricing($_data);
                    $newHotelAccommodationsPricing->save();
                }
                
            }
        }
        if(!empty($offer->images)){
            foreach($offer->images as $image){
                $newImage = new Image();
                $newImage->fill($image->toArray());
                $newImage->offer_id = $newOffer->id;
                $newImage->save();
            }
        }

        if(!empty($offer->hotelOffer->observations)){
            $newHotelOffer->observations()->sync($offer->hotelOffer->observations->pluck('id')->toArray());
        }
        if (!empty($offer->hotelOffer->hotel->structures)) {
            $newHotel->structures()->sync($offer->hotelOffer->hotel->structures->pluck('id')->toArray());
        }

        return $newOffer;
    }

    /**
     * [replicateBustripRelatioships description]
     *
     * @param   Offer  $offer     [$offer description]
     * @param   Offer  $newOffer  [$newOffer description]
     *
     * @return  Offer             [return description]
     */
    protected function replicateBustripRelatioships(Offer $offer, Offer $newOffer, Request $request): Offer
    {
        $_request_data = $request->toArray();
        
        $currency               = $this->replicateCurrency($offer, $newOffer, $_request_data);
        
        foreach ($offer->bustripRoutes as $bustripRoute) {
            $exists = 0;
            // Rule: checking if there is any bustripBoardingLocation sent to create the route.
            foreach ($bustripRoute->bustripBoardingLocations as $bustripBoardingLocation) {
                if(isset($_request_data['boarding_at'][$bustripBoardingLocation->id])){
                    $exists = 1;
                    break;
                }
            }
            if(!$exists){
                continue;
            }
            $newBustripRoute = $bustripRoute->replicate();
            $newBustripRoute->offer_id  = $newOffer->id;
            $newBustripRoute->fields    = ['sale_dates' => null];
            $newBustripRoute->push();

            foreach ($bustripRoute->bustripBoardingLocations as $bustripBoardingLocation) {
                if(!isset($_request_data['boarding_at'][$bustripBoardingLocation->id])){
                    continue;
                }
                $newBustripBoardingLocations = $bustripBoardingLocation->replicate();
                $newBustripBoardingLocations->bustrip_route_id = $newBustripRoute->id;
                
                if(isset($_request_data['boarding_at'][$bustripBoardingLocation->id])){
                    $boarding_at = convertDatetime($_request_data['boarding_at'][$bustripBoardingLocation->id]);
                    $newBustripBoardingLocations->boarding_at = $boarding_at;
                }


                $newBustripBoardingLocations->push();

                app(BustripBoardingLocationRepository::class)->handleAddress($newBustripBoardingLocations, $bustripBoardingLocation->address->toArray());

                /*
                if (!empty($bustripBoardingLocation->additionals)) {
                    $newBustripBoardingLocations->additionals()->sync($bustripBoardingLocation->additionals->pluck('id')->toArray());
                }
                */
            }

            if (!empty($bustripRoute->inclusions)) {
                $newBustripRoute->inclusions()->sync($bustripRoute->inclusions->pluck('id')->toArray());
            }

            if (!empty($bustripRoute->exclusions)) {
                $newBustripRoute->exclusions()->sync($bustripRoute->exclusions->pluck('id')->toArray());
            }

            if (!empty($bustripRoute->observations)) {
                $newBustripRoute->observations()->sync($bustripRoute->observations->pluck('id')->toArray());
            }            
        }

        return $newOffer;
    }

    /**
     * [replicateShuttleRelatioships description]
     *
     * @param   Offer  $offer     [$offer description]
     * @param   Offer  $newOffer  [$newOffer description]
     *
     * @return  Offer             [return description]
     */
    protected function replicateShuttleRelatioships(Offer $offer, Offer $newOffer, Request $request): Offer
    {
        $_request_data = $request->toArray();
        
        $currency               = $this->replicateCurrency($offer, $newOffer, $_request_data);
        
        foreach ($offer->shuttleRoutes as $shuttleRoute) {
            $exists = 0;
            // Rule: checking if there is any shuttleBoardingLocation sent to create the route.
            foreach ($shuttleRoute->shuttleBoardingLocations as $shuttleBoardingLocations) {
                if(isset($_request_data['boarding_at'][$shuttleBoardingLocations->id])){
                    $exists = 1;
                    break;
                }
            }
            if(!$exists){
                continue;
            }
            $newShuttleRoute = $shuttleRoute->replicate();
            $newShuttleRoute->offer_id = $newOffer->id;
            $newShuttleRoute->fields  = ['sale_dates' => null];
            $newShuttleRoute->push();

            foreach ($shuttleRoute->shuttleBoardingLocations as $shuttleBoardingLocation) {
                if(!isset($_request_data['boarding_at'][$shuttleBoardingLocation->id])){
                    continue;
                }
                $newShuttleBoardingLocations = $shuttleBoardingLocation->replicate();
                $newShuttleBoardingLocations->shuttle_route_id = $newShuttleRoute->id;

                if(isset($_request_data['boarding_at'][$shuttleBoardingLocation->id])){
                    $boarding_at = convertDatetime($_request_data['boarding_at'][$shuttleBoardingLocation->id]);
                    $newShuttleBoardingLocations->boarding_at = $boarding_at;
                }
                $newShuttleBoardingLocations->push();

                app(ShuttleBoardingLocationRepository::class)->handleAddress($newShuttleBoardingLocations, $shuttleBoardingLocation->address->toArray());

                /* 
                $_newAdditionals    = null;
                if (!empty($shuttleBoardingLocation->additionals)) {
                    $_oldAdditionals    = $shuttleBoardingLocation->additionals->pluck('id')->toArray();
                    foreach($_oldAdditionals as $old){
                        $oldAdditional  = Additional::find($old);
                        $_data          = $oldAdditional->toArray();
                        $name           = json_encode($_data['name']);
                        $additional     = Additional::where("package_id", $newOffer->package_id)
                            ->where("provider_id", $newOffer->provider_id)
                            ->where("name", $name)
                            ->first();
                        if($additional){
                            $_newAdditionals[] = $additional->id;
                        }
                    }
                    $newShuttleBoardingLocations->additionals()->sync($_newAdditionals);
                }
                */
            }

            if (!empty($shuttleRoute->inclusions)) {
                $newShuttleRoute->inclusions()->sync($shuttleRoute->inclusions->pluck('id')->toArray());
            }

            if (!empty($shuttleRoute->exclusions)) {
                $newShuttleRoute->exclusions()->sync($shuttleRoute->exclusions->pluck('id')->toArray());
            }

            if (!empty($shuttleRoute->observations)) {
                $newShuttleRoute->observations()->sync($shuttleRoute->observations->pluck('id')->toArray());
            }
        }

        return $newOffer;
    }

    /**
     * [replicateAdditionalRelatioships description]
     *
     * @param   Offer  $offer     [$offer description]
     * @param   Offer  $newOffer  [$newOffer description]
     *
     * @return  Offer             [return description]
     */
    protected function replicateAdditionalRelatioships(Offer $offer, Offer $newOffer, Request $request): Offer
    {
        $_request_data          = $request->toArray();
        $currency               = $this->replicateCurrency($offer, $newOffer, $_request_data);
        foreach ($offer->additionalGroups as $additionalGroup) {
            $newAdditionalGroup = $additionalGroup->replicate();
            $newAdditionalGroup->offer_id = $newOffer->id;
            $newAdditionalGroup->push();

            foreach ($additionalGroup->additionals as $additional) {
                $newAdditional = $additional->replicate();
                $newAdditional->additional_group_id = $newAdditionalGroup->id;
                $newAdditional->offer_id            = $newOffer->id;
                $newAdditional->currency            = $currency;
                $newAdditional->package_id          = $newOffer->package_id;
                $newAdditional->push();
            }
        }
        return $newOffer;
    }

    /**
     * [replicateCurrency description]
     *
     * @param   Offer  $offer     [$offer description]
     * @param   Offer  $newOffer  [$newOffer description]
     * @param   type   $attributes  [$attributes description]
     *
     * @return  Offer             [return description]
     */
    protected function replicateCurrency(Offer $offer, Offer &$newOffer, $attributes){
        if(isset($attributes['currency']) && in_array($attributes['currency'], Currency::toArray())){
            $currency                   = $attributes['currency'];
            $newOffer->currency         = $currency;
            $newOffer->save();
        }else{
            $currency                   = $offer->currency;
        }
        return $currency;
    }
}
