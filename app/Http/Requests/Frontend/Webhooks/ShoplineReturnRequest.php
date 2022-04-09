<?php

namespace App\Http\Requests\Frontend\Webhooks;

use Illuminate\Foundation\Http\FormRequest;

class ShoplineReturnRequest extends FormRequest
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
            'pedido'        => 'required',
            'chave'         => 'required',
            'codEmp'        => 'required'
        ];
    }
}
