<?php

namespace App\Http\Requests\Backend\Offers;

use App\Models\LongtripAccommodation;
use App\Rules\Offers\LongTripAccommodationHotelValidation;
use App\Rules\Offers\LongTripAccommodationValidation;
use Illuminate\Foundation\Http\FormRequest;

class LongTripAccommodationHotelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $entity_address         = 'address.';
        $entity_hotel           = 'hotel.';
        $_data                                  = count($this->json()->all()) ? $this->json()->all() : $this->all();

        return [
            'longtrip_accommodation_id'         => ['required', 'integer', 'exists:App\Models\LongtripAccommodation,id'],
            'longtrip_accommodation_type_id'    => ['required', 'integer'],
            'hotel_id'                          => ['nullable', 'exists:hotels,id', new LongTripAccommodationHotelValidation($this->route('provider'), $_data)],
            'checkin'                       => ['required', 'string', "date_format:d/m/Y", new LongTripAccommodationValidation($this->route('offer'), $this->route('longtripRoute'), $this->route('longtripAccommodationHotel'), $_data['longtrip_accommodation_type_id'], "ci", $_data)],
            'checkout'                      => ['required', 'string', "date_format:d/m/Y", new LongTripAccommodationValidation($this->route('offer'), $this->route('longtripRoute'), $this->route('longtripAccommodationHotel'), $_data['longtrip_accommodation_type_id'], "co", $_data)],
            $entity_hotel.'name'            => ['nullable', 'string'],
            $entity_hotel.'checkin'         => ['nullable', 'string', 'date_format:H:i'],
            $entity_hotel.'checkout'        => ['nullable', 'string', 'date_format:H:i'],
            $entity_address.'country'       => ['nullable', 'string'],
            $entity_address.'state'         => ['nullable'],
            $entity_address.'city'          => ['nullable', 'string'],
            $entity_address.'address'       => ['nullable', 'string'],
            $entity_address.'zip'           => ['nullable'],
            $entity_address.'number'        => ['nullable'],
            $entity_address.'neighborhood'  => ['nullable'],
            $entity_address.'complement'    => ['nullable'],
            $entity_address.'latitude'      => ['nullable'],
            $entity_address.'longitude'     => ['nullable'],
            
        ];
    }
}
