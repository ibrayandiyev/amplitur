<?php

namespace App\Exceptions\Bookings;

use Exception;

class InvalidPaymentOperationException extends Exception
{
    public $payload;

    public function __construct($message=null, $code=null, $previous = null, $payload = null)
    {
        $this->payload = $payload;
        if(!$message){
            $message = "This operation is invalid.";
        }
        parent::__construct($message, $code, $previous);
    }
}