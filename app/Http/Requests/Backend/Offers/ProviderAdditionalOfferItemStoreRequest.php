<?php

namespace App\Http\Requests\Backend\Offers;

use App\Enums\OfferType;
use Illuminate\Foundation\Http\FormRequest;

class ProviderAdditionalOfferItemStoreRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $canChange = true;
        if(isset($this->offer)){
            $canChange = !(user()->isProvider());
        }
        return [
            'fields.saledates' =>  ['nullable', 'array'],
            'type'      =>  ['required', 'array', 'in:'. OfferType::toString()],
            'price'     =>  ['required'],
            'stock'     =>  ['required', 'integer'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
