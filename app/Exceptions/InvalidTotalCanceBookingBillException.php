<?php

namespace App\Exceptions;

use Exception;

class InvalidTotalCanceBookingBillException extends Exception
{
    public $payload;

    public function __construct($message=null, $code=null, $previous = null)
    {
        parent::__construct("Invalid total value for cancellation the booking bill", $code, $previous);
    }
}