<?php

namespace App\Exceptions;

use Exception;

class PaymentErrorException extends Exception
{
    public $payload;

    public function __construct($message, $code, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}