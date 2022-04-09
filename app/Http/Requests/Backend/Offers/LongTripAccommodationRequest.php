<?php

namespace App\Http\Requests\Backend\Offers;

use Illuminate\Foundation\Http\FormRequest;

class LongTripAccommodationRequest extends FormRequest
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
        $_data                                  = count($this->json()->all()) ? $this->json()->all() : $this->all();

        return [
            'longtrip_accommodation_type_id'    => ['required', 'exists:longtrip_accommodation_types,id']
        ];
    }
}
