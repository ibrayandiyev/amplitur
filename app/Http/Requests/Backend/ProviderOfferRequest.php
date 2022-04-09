<?php

namespace App\Http\Requests\Backend;

use App\Enums\Currency;
use Illuminate\Foundation\Http\FormRequest;

class ProviderOfferRequest extends FormRequest
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
        $canChange = true;
        if(isset($this->offer)){
            $canChange = !(user()->isProvider() && $this->offer->hasBookings());
        }
        $_validation = [
            'package_id' => $canChange ? 'required' : '' . '|exists:packages,id',
            'expires_at' => ($canChange ? 'required' : 'nullable') . '|date_format:"d/m/Y, H:i"',
            'currency' => $canChange ? 'required' : '' . '|in:' . Currency::toString(),
            'hotel_offer.minimum_stay' => 'nullable',
        ];
        return $_validation;
    }
}
