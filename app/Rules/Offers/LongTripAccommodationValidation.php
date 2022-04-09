<?php

namespace App\Rules\Offers;

use App\Repositories\LongtripAccommodationHotelRepository;
use App\Repositories\LongtripAccommodationRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class LongTripAccommodationValidation implements Rule
{
    private $type                       = "ci";
    private $offer                      = null;
    private $attribute                  = null;
    private $messageType                = null;
    private $longTripAccommodationTypeId= null;
    private $longTripRoute              = null;
    private $longTripAccommodationHotel = null;
    private $hotelId                    = null;
    private $_requestData               = null;
    
    public const TYPE_CHECKIN       = "ci";
    public const TYPE_CHECKOUT      = "co";

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($offer = null, $longTripRoute=null, $longTripAccommodationHotel=null, $longTripAccommodationTypeId=null, $type=null, $_requestData=null)
    {
        $this->type                                 = $type;
        $this->offer                                = $offer;
        $this->longTripAccommodationTypeId          = $longTripAccommodationTypeId;
        $this->longTripRoute                        = $longTripRoute;
        $this->longTripAccommodationHotel           = $longTripAccommodationHotel;
        $this->messageType                          = 1;
        $this->_requestData                         = $_requestData;
        $this->longtripAccommodationRepository      = app(LongtripAccommodationRepository::class);
        $this->longtripAccommodationHotelRepository = app(LongtripAccommodationHotelRepository::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $doubleCheck            = false;
        $this->messageType      = 1;
        if($this->offer == null){ return true;}
        $package = ($this->offer->package);
        $this->attribute = $attribute;

        $accommodationHotelId   = $this->cleanAttributeName($attribute);
        if(isset($this->_requestData['hotel_id']) && 
            isset($this->_requestData["quick"][$accommodationHotelId]["hotel_id"])
        ){
            $this->hotelId          = isset($this->_requestData['hotel_id'])?$this->_requestData['hotel_id']:$this->_requestData["quick"][$accommodationHotelId]["hotel_id"];
        }
        if($this->longTripAccommodationHotel == null){
            if($accommodationHotelId != ""){
                $this->longTripAccommodationHotel = $this->longtripAccommodationHotelRepository->find($accommodationHotelId);
            }
        }
        // Coming from quick add
        if(isset($this->_requestData["quick"][$accommodationHotelId]["longtrip_accommodation_type_id"])){
            $this->longTripAccommodationTypeId = $this->_requestData["quick"][$accommodationHotelId]["longtrip_accommodation_type_id"];
        }
        // Rule 1: The dates must be in the same range of the package.
        switch($this->type){
            case LongTripAccommodationValidation::TYPE_CHECKIN:
                $currentDate   = Carbon::createFromFormat("d/m/Y H:i:s", $value ." 00:00:00" );
                break;
            case LongTripAccommodationValidation::TYPE_CHECKOUT:
                $currentDate     = Carbon::createFromFormat("d/m/Y H:i:s", $value ." 23:59:59" );
                break;
        }
        $intervalDays           = $currentDate->between($package->starts_at->sub(30, 'days')->startOfDay(), $package->starts_at->add(30,'days')->endOfDay());
        if(!$intervalDays){
            return false;
        }
        $this->messageType = 2;
        // Rule 3 and 4: Check Long trip routes collisions
        $result = $this->longTripRoute->longtripAccommodations->where('longtrip_accommodation_type_id', $this->longTripAccommodationTypeId);
        if($result){
            foreach($result as $lta){
                foreach($lta->longtripAccommodationHotels()->orderBy("checkin")->get() as $lah){
                    if(
                        $accommodationHotelId == $lah->id){
                        continue;
                    }
                    if($this->hotelId != $lah->hotel_id){
                        continue;
                    }
                    if(isset($this->_requestData["quick"][$lah->id]['checkin'])){
                        $checkin    = Carbon::createFromFormat("d/m/Y H:i:s", $this->_requestData["quick"][$lah->id]['checkin'] ." 00:00:00" );
                    }else{
                        $checkin    = $lah->checkin;

                    }
                    if(isset($this->_requestData["quick"][$lah->id]['checkout'])){
                        $checkout   = Carbon::createFromFormat("d/m/Y H:i:s", $this->_requestData["quick"][$lah->id]['checkout'] ." 23:59:59" );
                    }else{
                        $checkout   = $lah->checkout;
                    }
                    $intervalDays       = $currentDate->between($checkin, $checkout);
                    if($intervalDays){
                        if($this->type == LongTripAccommodationValidation::TYPE_CHECKIN){
                            if(!$currentDate->isSameDay($checkout->startOfDay())){
                                $doubleCheck = true;
                            }
                        }
                        if($this->type == LongTripAccommodationValidation::TYPE_CHECKOUT){
                            if(!$currentDate->isSameDay($checkin->startOfDay())){
                                $doubleCheck = true;
                            }
                        }
                        if($doubleCheck){
                            return false;
                        }
                    }
                }
            }
            
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {

        switch($this->messageType){
            case 2:
                if(preg_match("/quick./", $this->attribute)){
                    $accommodationHotelId = $this->cleanAttributeName($this->attribute);
                    switch($this->type){
                        case LongTripAccommodationValidation::TYPE_CHECKIN:
                            $type = " - Checkin";

                            break;
                        case LongTripAccommodationValidation::TYPE_CHECKOUT:
                            default:
                            $type = " - Checkout";
                            break;
                    }
                    $this->attribute = $this->_requestData["quick"][$accommodationHotelId]["name"] ."(". $this->_requestData["quick"][$accommodationHotelId]["accommodation_name"] ." $type)";
                }
                return str_replace("%1", $this->attribute, 'The current date for %1 is crossing with another hotel date in this route.');
                break;
            case 1:
            default:
                return str_replace("%1", $this->attribute, 'The current date for %1 cannot be greater or less then 30 days of package date.');
                break;
        }
    }

    private function cleanAttributeName($attribute=""){
        return preg_replace("/(quick.|.checkin|.checkout)/", "", $attribute);
    }
}
