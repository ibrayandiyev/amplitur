<?php

namespace App\Http\Requests\Backend\Offers;

use Illuminate\Foundation\Http\FormRequest;

class BustripRouteBoardingLocationStoreRequest extends FormRequest
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
        $entity                 = '';
        $entity_address         = 'address.';

        return [
            'boarding_at'       => ['required', 'string'],
            'travel_time'       => ['required', 'integer'],
            'price'             => ['required'],
            $entity_address.'country'       => ['nullable', 'exists:countries,iso2'],
            $entity_address.'state'         => ['nullable', 'exists:states,iso2'],
            $entity_address.'city'          => ['nullable', 'exists:cities,id'],
            $entity_address.'address'       => ['required', 'string'],
        ];
    }
}
