<?php

namespace App\Http\Requests\Frontend;

use App\Enums\PaymentMethod as EnumsPaymentMethod;
use App\Enums\PersonType;
use App\Models\PaymentMethod;
use App\Repositories\PaymentMethodRepository;
use Illuminate\Foundation\Http\FormRequest;

class FormPaymentRequest extends FormRequest
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
        $_data              = count($this->json()->all()) ? $this->json()->all() : $this->all();
        $paymentMethod      = app(PaymentMethodRepository::class)->find($_data["formapag"]);
        if($paymentMethod && $paymentMethod->isCreditCard()){
            return [
                'formapag'      => 'required',
                'parcelas'      => 'required',
                'ct-numero'     => 'required',
                'ct-cvc'        => 'required',
                'ct-nome'       => 'required',
                'ct-mes'        => 'required',
                'ct-ano'        => 'required',
            ];
        }

        return [
            'formapag'      => 'required',
            'parcelas'      => 'required',
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
            'formapag.required' => __('frontend.reservas.missing_payment_form'),
            'parcelas.required' => __('frontend.reservas.missing_installments')
        ];
    }

    /**
     * @inherited
     */
    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->after(function() use ($validator) {
            $valid  = true;
            $input  = $this->request->all();

            $payment_method_id = isset($input['formapag'])?$input['formapag']:null;

            $paymentMethod      = app(PaymentMethod::class)->find($payment_method_id);
            if($paymentMethod){
                switch($paymentMethod->code){
                    case EnumsPaymentMethod::PM_CODE_CREDIT_CARD:
                        $valid = (isset($input['parcelas']) && is_numeric($input['parcelas'])?$valid & true:$valid & false);
                        $valid = (isset($input['ct-cvc']) && $input['ct-cvc']!= "" ?$valid & true:$valid & false);
                        $valid = (isset($input['ct-mes']) && is_numeric($input['ct-mes'])?$valid & true:$valid & false);
                        $valid = (isset($input['ct-ano']) && is_numeric($input['ct-ano'])?$valid & true:$valid & false);
                        break;
                }
            }

            if(!$valid) {
                $validator->errors()->add('formapag', __('resources.payment-methods.credit-card-error'));
            }

        });

        return $validator;
    }
}
