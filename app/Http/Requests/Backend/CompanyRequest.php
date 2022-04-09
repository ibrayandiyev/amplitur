<?php

namespace App\Http\Requests\Backend;

use App\Enums\ContactType;
use App\Enums\Currency;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
        $companyId  = $this->route('company');

        $updateNotRequired = "required";

        if($companyId != null){
            $updateNotRequired = "nullable";
        }
        return [
            'company_name' => 'required',
            'legal_name' => 'required',
            'website' => '',
            'logo' => '',
            'registry' => 'required',
            'status' => $updateNotRequired,
            'language' => 'required',
            'terms_use' => 'required',

            'address.address'       => $updateNotRequired,
            'address.number'        => $updateNotRequired,
            'address.neighborhood'  => ($updateNotRequired == 'required')?'required_if:address.country,BR':$updateNotRequired,
            'address.complement'    => 'nullable',
            'address.zip' => ($updateNotRequired == 'required')?'required_if:address.country,BR':$updateNotRequired,
            'address.city' => 'required',
            'address.state' => 'required',
            'address.country' => 'required',

            'bank_account.currency' => $updateNotRequired.'|in:' . Currency::toString(),

            'bank_account.BRL.bank' => 'required_if:bank_account.currency,' . Currency::REAL,
            'bank_account.BRL.agency' => 'required_if:bank_account.currency,' . Currency::REAL,
            'bank_account.BRL.account_type' => 'required_if:bank_account.currency,' . Currency::REAL,
            'bank_account.BRL.account_number' => 'required_if:bank_account.currency,' . Currency::REAL,

            'bank_account.USD.bank' => 'required_if:bank_account.currency,' . Currency::DOLLAR,
            'bank_account.USD.wire' => 'required_if:bank_account.currency,' . Currency::DOLLAR,
            'bank_account.USD.routing_number' => 'required_if:bank_account.currency,' . Currency::DOLLAR,
            'bank_account.USD.account_number' => 'required_if:bank_account.currency,' . Currency::DOLLAR,

            'bank_account.GBP.iban' => 'required_if:bank_account.currency,' . Currency::LIBRA,
            'bank_account.GBP.sort_code' => 'required_if:bank_account.currency,' . Currency::LIBRA,
            'bank_account.GBP.account_number' => 'required_if:bank_account.currency,' . Currency::LIBRA,

            'bank_account.EUR.bank' => 'required_if:bank_account.currency,' . Currency::EURO,
            'bank_account.EUR.iban' => 'required_if:bank_account.currency,' . Currency::EURO,

            'contacts.responsible.0' => 'required',
            'contacts.type.0' => 'required|in:' . ContactType::FINANCIAL_PHONE,
            'contacts.value.0' => 'required', 
            'contacts.type.1' => 'required|in:' . ContactType::FINANCIAL_EMAIL,
            'contacts.value.1' => 'required', 
            
            'contacts.responsible.1' => 'required',
            'contacts.value.2' => 'required', 
            'contacts.type.2' => 'required|in:' . ContactType::BOOKING_PHONE,
            'contacts.type.3' => 'required|in:' . ContactType::BOOKING_EMAIL,
            'contacts.value.3' => 'required',
        ];
    }

    /**
     * @inherited
     */
    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->sometimes('documents', 'required|min:1', function ($input) {
            return empty($this->company->id) || $this->company->documents->count() == 0;
        });

        $validator->sometimes('documents.*', 'file', function ($input) {
            return empty($this->company->id) || $this->company->documents->count() == 0;
        });

        return $validator;
    }
}
