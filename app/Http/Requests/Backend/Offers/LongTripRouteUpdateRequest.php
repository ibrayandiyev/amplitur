<?php

namespace App\Http\Requests\Backend\Offers;

use App\Rules\Offers\LongTripAccommodationHotelValidation;
use App\Rules\Offers\LongTripAccommodationValidation;
use Illuminate\Foundation\Http\FormRequest;

class LongTripRouteUpdateRequest extends FormRequest
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
        $entity                     = '';
        $entity_fields              = 'fields.';
        $entity_extra_observations  = 'extra_observations.';
        $entity_extra_inclusions    = 'extra_inclusions.';
        $entity_extra_exclusions    = 'extra_exclusions.';
        $entity_quick               = 'quick.*.';
        $_data                                  = count($this->json()->all()) ? $this->json()->all() : $this->all();

        return [
            'name'                              => ['required', 'string'],
            'capacity'                          => ['required', 'integer'],
            'longtrip_accommodation_type_id'    => ['required', 'integer'],
            'name'                              => ['required', 'string'],
            $entity_quick.'id'                  => ['required'],
            $entity_quick.'hotel_id'            => ['required', 'exists:hotels,id', new LongTripAccommodationHotelValidation($this->route('provider'), $_data)],
            $entity_quick.'checkin'             => ['required', 'string', "date_format:d/m/Y", new LongTripAccommodationValidation($this->route('offer'), $this->route('longtripRoute'), $this->route('longtripAccommodationHotel'), $_data['longtrip_accommodation_type_id'], "ci", $_data)],
            $entity_quick.'checkout'            => ['required', 'string', "date_format:d/m/Y", new LongTripAccommodationValidation($this->route('offer'), $this->route('longtripRoute'), $this->route('longtripAccommodationHotel'), $_data['longtrip_accommodation_type_id'], "co", $_data)],
        ];
    }
}
