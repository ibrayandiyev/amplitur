<?php

namespace App\Exceptions\Bookings;

use Exception;

class BillApproveFailException extends Exception
{
    public $payload;

    public function __construct($message=null, $code=null, $previous = null, $payload=null)
    {
        $this->payload      = $payload;
        parent::__construct("This bill was not approved. Check with administrator.", $code, $previous);
    }
}