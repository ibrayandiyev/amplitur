<?php

namespace App\Exceptions;

use Exception;

class NoStockException extends Exception
{
    public function __construct($message=null, $code=null, $previous = null)
    {
        if($message == null){
            $message = __("backend.package.no_stock");
        }
        parent::__construct($message, $code, $previous);
    }
}
