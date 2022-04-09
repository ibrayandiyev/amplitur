<?php

namespace App\Rules\General;

use DateTime;
use Illuminate\Contracts\Validation\Rule;

class DateValidation implements Rule
{
    private $_attribute            = null;
    private $_requestData          = null;
    private $_format               = null;
    
     /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($_requestData=null, $format="Y-m-d")
    {
        $this->_requestData     = $_requestData;
        $this->_format          = $format;
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
        $this->_attribute    = $attribute;
        return DateTime::createFromFormat($this->_format, $value);
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $string =  str_replace("%1", $this->_attribute, 'The date for field %1 is not equal to this format %2');
        $string = str_replace("%2", $this->_format, $string);
        return $string;
                
    }
}
