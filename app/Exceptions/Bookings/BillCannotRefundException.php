<?php

namespace App\Exceptions\Bookings;

use Exception;

class BillCannotRefundException extends Exception
{
    public $payload;

    public function __construct($message=null, $code=null, $previous = null)
    {
        parent::__construct("This bill cannot be refunded", $code, $previous);
    }
}