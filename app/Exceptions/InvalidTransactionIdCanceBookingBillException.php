<?php

namespace App\Exceptions;

use Exception;

class InvalidTransactionIdCanceBookingBillException extends Exception
{
    public $payload;

    public function __construct($message=null, $code=null, $previous = null)
    {
        parent::__construct("Invalid transaction value for cancellation the booking bill", $code, $previous);
    }
}