<?php

namespace App\Rules\Offers;

use App\Repositories\HotelRepository;
use App\Repositories\LongtripAccommodationHotelRepository;
use App\Repositories\LongtripAccommodationRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class LongTripAccommodationHotelValidation implements Rule
{
    private $attribute                  = null;
    private $hotel                      = null;
    private $hotelRepository            = null;
    private $provider                   = null;
    private $_requestData               = null;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($provider=null, $_requestData=null)
    {
        $this->hotelRepository                      = app(HotelRepository::class);
        $this->provider                             = $provider;
        $this->_requestData                         = $_requestData;
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
        $this->attribute    = $attribute;
        $this->hotel        = $this->hotelRepository->find($value);
        if(!$this->hotel){
            return false;
        }
        if($this->hotel->provider_id != $this->provider->id){
            return false;
        }
        return true;
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return str_replace("%1", $this->attribute, 'The hotel %1 is not related to the provider.');
                
    }
}
