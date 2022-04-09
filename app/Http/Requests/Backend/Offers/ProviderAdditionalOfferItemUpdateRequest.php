<?php

namespace App\Http\Requests\Backend\Offers;

use App\Enums\OfferType;
use Illuminate\Foundation\Http\FormRequest;

class ProviderAdditionalOfferItemUpdateRequest extends ProviderAdditionalOfferItemStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $_rules =  parent::rules();
        unset($_rules['type']);
        return $_rules;
    }
}
