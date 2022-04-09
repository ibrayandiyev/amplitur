<?php

namespace App\Repositories\Concerns;

trait ActionErrors
{

    protected $_errorMessages = [];

    /**
     * @var integer
     */
    protected $hasErrors = 0;

    /**
     * [addErrorMessage description]
     *
     * @param   string  $message         [$message description]
     * 
     * @return  array                    [return description]
     */
    public function addErrorMessage(string $message)
    {
        $this->_errorMessages[] = $message;
        $this->hasErrors = 1;
        return $this->_errorMessages;
    }

    /**
     * [getErrorMessages description]
     *
     * @return  array                    [return description]
     */
    public function getErrorMessages()
    {
        return $this->_errorMessages;
    }
    
    /**
     * [hasErrors description]
     *
     * @return  int                    [return description]
     */
    public function hasErrors(): int
    {
        return $this->hasErrors;
    }

    /**
     * [setError description]
     *
     * @return  int                    [return description]
     */
    public function setError(): int
    {
        $this->hasErrors = 1;
        return $this->hasErrors;
    }
}
