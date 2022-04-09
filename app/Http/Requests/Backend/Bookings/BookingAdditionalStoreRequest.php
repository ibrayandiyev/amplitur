<?php

namespace App\Http\Requests\Backend\Bookings;

use Illuminate\Foundation\Http\FormRequest;

class BookingAdditionalStoreRequest extends FormRequest
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
            $entity.'additional_id'         => ['required'],
            $entity.'price'                 => ['required']
            
        ];
    }
}