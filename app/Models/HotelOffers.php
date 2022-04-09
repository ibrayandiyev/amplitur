<?php

namespace App\Models;

use App\Models\Relationships\BelongsToCategory;
use App\Models\Relationships\BelongsToHotel;
use App\Models\Relationships\BelongsToManyHotelStructures;
use App\Models\Relationships\BelongsToOffer;
use App\Models\Relationships\HasManyHotelAccommodations;
use App\Models\Relationships\HasManyLongtripAccommodations;
use App\Models\Relationships\HasOneHotel;
use App\Models\Relationships\MorphManyInclusions;
use App\Models\Relationships\MorphManyObservations;
use App\Models\Relationships\MorphOneAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HotelOffers extends Model
{
    public $timestamps = false;
    
    use BelongsToOffer,
        HasOneHotel,
        BelongsToCategory,
        MorphManyInclusions,
        MorphOneAddress,
        HasManyHotelAccommodations,
        BelongsToManyHotelStructures,
        HasManyLongtripAccommodations,
        MorphManyObservations,
        HasTranslations,
        HasFactory;

    protected $fillable = [
        'offer_id',
        'hotel_id',
        'minimum_stay'
    ];

    protected $translatable = [
        'accommodations',
    ];

    /**
     * Get all board locations formatted as list
     *
     * @return  string
     */
    public function getAccommodations($_data=[])
    {
        $order = null;
        extract($_data);
        $accommodations = $this->accommodations();
        if($order !=null){
            $accommodations = $accommodations->orderBy($order);
        }
        $accommodations = $accommodations->get();
        
        $list = '';

        foreach ($accommodations as $accommodation) {

            $list .= $accommodation->typeLabel. '<BR>';
        }

        return $list;
    }

    /**
     * Get all board locations formatted as list
     *
     * @return  string
     */
    public function getMinAccommodationsPricingsPrice()
    {
        $list = '';
        foreach($this->accommodations as $accommodation){
            $hotelAccommodation =  $accommodation->hotelAccommodationsPricings()->orderBy("price", "ASC")->first();
            $list .= money($hotelAccommodation->price, $this->offer->currency). '</BR>';
        }
        
        return $list;
    }

    /**
     * Get all board locations formatted as list
     *
     * @return  string
     */
    public function getMinAccommodationsPricingsNetprice()
    {
        $list = '';
        foreach($this->accommodations as $accommodation){
            $hotelAccommodation =  $accommodation->hotelAccommodationsPricings()->orderBy("price", "ASC")->first();
            $list .= money($hotelAccommodation->getPriceNet(), $this->offer->currency). '</BR>';
        }
        
        return $list;
    }
}
