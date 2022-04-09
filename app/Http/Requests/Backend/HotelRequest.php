<?php

namespace App\Http\Requests\Backend;

use App\Enums\OfferType;
use Illuminate\Foundation\Http\FormRequest;

class HotelRequest extends FormRequest
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
        $entity                     = 'hotel.';
        $entity_address             = 'address.';
        $_data                      = count($this->json()->all()) ? $this->json()->all() : $this->all();

        return [
            $entity.'category_id'           => ['required', 'exists:categories,id'],
            $entity.'provider_id'           => ['nullable', 'exists:providers,id'],
            $entity.'name'                  => ['required'],
            $entity.'checkin'               => ['required', 'date_format:H:i'],
            $entity.'checkout'              => ['required', 'date_format:H:i'],
            $entity.'registry_type'         => ['required', 'in:'.OfferType::LONGTRIP],
            $entity_address.'country'       => ['required', 'string'],
            $entity_address.'state'         => ['required'],
            $entity_address.'city'          => ['required', 'string'],
            $entity_address.'address'       => ['required', 'string'],
            $entity_address.'zip'           => ['nullable'],
            $entity_address.'number'        => ['nullable'],
            $entity_address.'neighborhood'  => ['nullable'],
            $entity_address.'complement'    => ['nullable'],
            $entity_address.'latitude'      => ['nullable'],
            $entity_address.'longitude'     => ['nullable'],
        ];
    }
}