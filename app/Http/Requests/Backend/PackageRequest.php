<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
            'event_id' => 'required|exists:events,id',
            'provider_id' => 'nullable|exists:providers,id',
            'starts_at' => 'required',
            'ends_at' => 'nullable',
            'location' => 'required',

            'address.address' => 'required',
            'address.number' => 'nullable',
            'address.neighborhood' => 'required_if:address.country,BR',
            'address.complement' => 'nullable',
            'address.zip' => 'required_if:address.country,BR',
            'address.city' => 'required',
            'address.state' => 'required',
            'address.country' => 'required',
        ];
    }
}
