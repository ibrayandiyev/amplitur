<?php

namespace App\Rules\Auth;

use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Contracts\Validation\Rule;

class LegalPersonTaxvatValidation implements Rule
{
    private $clientRepository           = null;
    private $clientId                   = null;
    private $_requestData               = null;
    
    public const TYPE_CHECKIN       = "ci";
    public const TYPE_CHECKOUT      = "co";

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($_requestData=null, $clientId)
    {
        $this->_requestData         = $_requestData;
        $this->clientId             = $clientId;
        $this->clientRepository     = app(ClientRepository::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value  = preg_replace("?[\/,.\\\-]*?", "", $value);

        $entity     = app(Client::class)->where("registry", $value);
        if($this->clientId != null){
            $entity = $entity->where("clientId", "!=", $this->clientId);
        }
        $entity     = $entity->first();
        if($entity){
            return false;
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __("validation.unique", ["attribute" => "TAX/VAT"]);
    }

    private function cleanAttributeName($attribute=""){
        return preg_replace("/(quick.|.checkin|.checkout)/", "", $attribute);
    }
}
