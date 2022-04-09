<?php

namespace App\Http\Requests\Frontend\Bookings;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
        return [
            'confirma'              => 'required',
            'confirmacontrato'      => 'required',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'confirma.required' => __('resources.bookings.no-continue-check'),
            'confirmacontrato.required' => __('resources.bookings.no-continue-check-contract')
        ];
    }

    /**
     * @inherited
     */
    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        return $validator;
    }
}
