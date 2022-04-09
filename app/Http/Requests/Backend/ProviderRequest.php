<?php

namespace App\Http\Requests\Backend;

use App\Enums\DocumentType;
use App\Enums\Gender;
use App\Enums\Language;
use App\Enums\PersonType;
use App\Rules\General\DateValidation;
use Illuminate\Foundation\Http\FormRequest;

class ProviderRequest extends FormRequest
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
        $providerId     = ($this->provider) ? $this->provider->id : null;
        $_data          = count($this->json()->all()) ? $this->json()->all() : $this->all();

        return [
            'name' => 'required_if:type,' . PersonType::FISICAL,
            'legal_name' => 'nullable|required_if:type,' . PersonType::LEGAL,
            'company_name' => 'nullable|required_if:type,' . PersonType::LEGAL,
            'email' => 'required|unique:providers,email,' . $providerId,
            'birthdate' => ['nullable', 'required_if:type,' . PersonType::FISICAL, new DateValidation($_data, dateByLanguage(language()))],
            'identity' => 'nullable|numeric',
            'document' => 'nullable|cpf',
            'uf' => '',
            'passport' => 'nullable|required_if:primary_document,' . DocumentType::PASSPORT,
            'registry' => 'nullable|required_if:type,' . PersonType::LEGAL . '|unique:providers,registry,' . $providerId,
            'gender' => 'nullable|in:' . Gender::toString() . '|required_if:type,' . PersonType::FISICAL,
            'language' => 'required|in:' . Language::toString(),
            'username' => 'required|min:6|unique:providers,username,' . $providerId,
            'password' => 'nullable|min:6|confirmed',
            'is_active' => 'boolean',
            'is_valid' => 'boolean',
            'is_newsletter_subscriber' => 'boolean',
            'type' => 'required|in:' . PersonType::toString(),
            'primary_document' => 'nullable',
            'responsible_name' => 'required_if:type,' . PersonType::LEGAL,
            'responsible_email' => 'required_if:type,' . PersonType::LEGAL,
            'terms_use' => 'required',

            'address.address' => 'required',
            'address.number' => 'required',
            'address.neighborhood' => 'required_if:address.country,BR',
            'address.complement' => 'nullable',
            'address.zip' => 'required_if:address.country,BR',
            'address.city' => 'required',
            'address.state' => 'required',
            'address.country' => 'required',

            'contacts' => 'required|min:1',
            'contacts.*.type' => 'nullable',
            'contacts.*.value' => 'nullable',
        ];
    }

    /**
     * @inherited
     */
    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->sometimes('primary_document', 'required', function ($input) {
            return $input->type != PersonType::LEGAL;
        });

        $validator->sometimes('document', 'required', function ($input) {
            return $input->type == PersonType::FISICAL && $input->address['country'] == 'BR';
        });
        
        $validator->sometimes('identity', 'required', function ($input) {
            return $input->type == PersonType::FISICAL && $input->primary_document == DocumentType::IDENTITY;
        });
        
        $validator->sometimes('registry', 'cnpj', function ($input) {
            return $input->type == PersonType::LEGAL && $input->address['country'] == 'BR';
        });

        $validator->sometimes('uf', 'required', function ($input) {
            return $input->address['country'] == 'BR' && $input->primary_document == DocumentType::IDENTITY;
        });

        $validator->sometimes('password', 'required', function ($input) {
            return !$this->provider;
        });

        return $validator;
    }
}
