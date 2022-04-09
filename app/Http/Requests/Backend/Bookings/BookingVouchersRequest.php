<?php

namespace App\Http\Requests\Backend\Bookings;

use App\Enums\OfferType;
use Illuminate\Foundation\Http\FormRequest;

class BookingVouchersRequest extends FormRequest
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
        $_data                      = count($this->json()->all()) ? $this->json()->all() : $this->all();

        return [
            $entity.'released_at'           => ['required', 'date_format:d/m/Y'],
            $entity.'services'              => ['required'],
            $entity.'comments'              => ['required'],
            
        ];
    }
}