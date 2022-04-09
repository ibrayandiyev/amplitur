<?php

namespace App\Exceptions;

use Exception;

class DeleteRelationsException extends Exception
{
    public $payload;

    public function __construct($message=null, $code=400, $previous = null)
    {
        if($message == null){
            $message = __('messages.remove_provider');
        }
        parent::__construct($message, $code, $previous);
    }
}
