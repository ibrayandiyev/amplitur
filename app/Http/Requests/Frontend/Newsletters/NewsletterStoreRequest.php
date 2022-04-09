<?php

namespace App\Http\Requests\Frontend\Newsletters;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class NewsletterStoreRequest extends FormRequest
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
            'name'      => 'required',
            'email'     => 'required',
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
            'name.required' => __('frontend.forms.name') ." ". __('frontend.forms.campos_obrigatorios2'),
            'email.required' => __('frontend.forms.email') ." ". __('frontend.forms.campos_obrigatorios2'),
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
