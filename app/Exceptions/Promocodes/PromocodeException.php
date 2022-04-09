<?php

namespace App\Exceptions\Promocodes;

use Exception;

class PromocodeException extends Exception
{
    public function __construct($message=null)
    {
        parent::__construct($message);
    }
}