<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Package;
use Illuminate\Support\Facades\DB;

class LowestOfferPriceService
{
    private $statusOffer = null;
    /**
     * [getPackageLowestPrice description]
     *
     * @param   Package  $package  [$package description]
     * @param   Offer    $offer    [$offer description]
     *
     * @return  [type]             [return description]
     */
    public function getPackageLowestPrice(Package $package, ?Offer $offer = null, $_params = [])
    {
        if(isset($_params['statusOffer']) && $_params['statusOffer'] != null){
            $this->statusOffer = $_params['statusOffer'];
        }

        $bustripLowestPrice = DB::select($this->getBustripLowestQuery($package, $offer));
        $shuttleLowestPrice = DB::select($this->getShuttleLowestQuery($package, $offer));
        $hotelLowestPrice = DB::select($this->getHotelLowestQuery($package, $offer));
        $longtripLowestPrice = DB::select($this->getLongtripLowestQuery($package, $offer));

        $prices = collect();

        if (isset($bustripLowestPrice[0]) && !empty($bustripLowestPrice[0])) {
            $prices->push([
                'price' => moneyFloat($bustripLowestPrice[0]->price, currency(), $bustripLowestPrice[0]->currency) * $bustripLowestPrice[0]->value,
                'currency' => currency()->code,
            ]);
        }

        if (isset($shuttleLowestPrice[0]) && !empty($shuttleLowestPrice[0])) {
            $prices->push([
                'price' => moneyFloat($shuttleLowestPrice[0]->price, currency(), $shuttleLowestPrice[0]->currency) * $shuttleLowestPrice[0]->value,
                'currency' => currency()->code,
            ]);
        }

        if (isset($hotelLowestPrice[0]) && !empty($hotelLowestPrice[0])) {    
            $prices->push([
                'price' => moneyFloat($hotelLowestPrice[0]->price, currency(), $hotelLowestPrice[0]->currency) * $hotelLowestPrice[0]->value,
                'currency' => currency()->code,
            ]);
        }

        if (isset($longtripLowestPrice[0]) && !empty($longtripLowestPrice[0])) {    
            $prices->push([
                'price' => moneyFloat($longtripLowestPrice[0]->price, currency(), $longtripLowestPrice[0]->currency),
                'currency' => currency()->code,
            ]);
        }

        if ($prices->isEmpty()) {
            return [
                'price' => 0,
                'currency' => currency()->code,
            ];
        }

        $lowestPrice = $prices->where('price', $prices->min('price'))->first();

        return $lowestPrice;
    }

    /**
     * [getBustripLowestQuery description]
     *
     * @param   Package  $package  [$package description]
     * @param   Offer    $offer    [$offer description]
     *
     * @return  string             [return description]
     */
    public function getBustripLowestQuery(Package $package, ?Offer $offer = null): string
    {
        $whereOffer = !is_null($offer) ? "AND o.id = {$offer->id}" : '';
        if($this->statusOffer != null){
            $whereOffer .= " AND o.status = '". $this->statusOffer ."'";
        }
        return "SELECT bbl.price, o.currency, sc.value
                  FROM bustrip_boarding_locations bbl
                  JOIN bustrip_routes br ON bbl.bustrip_route_id = br.id
                  JOIN offers o ON br.offer_id = o.id
                  JOIN packages p ON o.package_id = p.id
                  JOIN sale_coefficients sc ON o.sale_coefficient_id = sc.id
                 WHERE p.id = {$package->id}
                   {$whereOffer}
                   AND bbl.price > 0
              ORDER BY bbl.price
                 LIMIT 1;
        ";
    }

    /**
     * [getShuttleLowestQuery description]
     *
     * @param   Package  $package  [$package description]
     * @param   Offer    $offer    [$offer description]
     *
     * @return  string             [return description]
     */
    public function getShuttleLowestQuery(Package $package, ?Offer $offer = null): string
    {
        $whereOffer = !is_null($offer) ? "AND o.id = {$offer->id}" : '';
        if($this->statusOffer != null){
            $whereOffer .= " AND o.status = '". $this->statusOffer ."'";
        }

        return "SELECT sbl.price, o.currency, sc.value
                  FROM shuttle_boarding_locations sbl
                  JOIN shuttle_routes sr on sbl.shuttle_route_id  = sr.id
                  JOIN offers o ON sr.offer_id = o.id
                  JOIN packages p ON o.package_id = p.id
                  JOIN sale_coefficients sc ON o.sale_coefficient_id = sc.id
                 WHERE p.id = {$package->id}
                   {$whereOffer}
                   AND sbl.price > 0
              ORDER BY sbl.price
                 LIMIT 1;
        ";
    }

    /**
     * [getHotelLowestQuery description]
     *
     * @param   Package  $package  [$package description]
     * @param   Offer    $offer    [$offer description]
     *
     * @return  string             [return description]
     */
    public function getHotelLowestQuery(Package $package, ?Offer $offer = null): string
    {
        $whereOffer = !is_null($offer) ? "AND o.id = {$offer->id}" : '';
        if($this->statusOffer != null){
            $whereOffer .= " AND o.status = '". $this->statusOffer ."'";
        }

        return "SELECT hap.price, o.currency, sc.value
                  FROM hotel_accommodations_pricings hap
                  JOIN offers o ON hap.offer_id = o.id
                  JOIN packages p ON o.package_id = p.id
                  JOIN sale_coefficients sc ON o.sale_coefficient_id = sc.id
                 WHERE p.id = {$package->id}
                   {$whereOffer}
                   AND hap.price > 0
              ORDER BY hap.price
                 LIMIT 1;
        ";
    }

    /**
     * [getLongtripLowestQuery description]
     *
     * @param   Package  $package  [$package description]
     * @param   Offer    $offer    [$offer description]
     *
     * @return  string             [return description]
     */
    public function getLongtripLowestQuery(Package $package, ?Offer $offer = null): string
    {
        $whereOffer = !is_null($offer) ? "AND o.id = {$offer->id}" : '';
        if($this->statusOffer != null){
            $whereOffer .= " AND o.status = '". $this->statusOffer ."'";
        }

        return "SELECT lap.price, o.currency, sc.value
                  FROM longtrip_accommodations_pricings lap
                  JOIN offers o ON lap.offer_id = o.id
                  JOIN packages p ON o.package_id = p.id
                  JOIN sale_coefficients sc ON o.sale_coefficient_id = sc.id
                 WHERE p.id = {$package->id}
                   {$whereOffer}
                   AND lap.price > 0
              ORDER BY lap.price
                 LIMIT 1;
        ";
    }
}