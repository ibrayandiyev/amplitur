<?php

namespace App\Repositories\Concerns;

trait ActionSuccess
{

    protected $_successMessages = [];

    /**
     * @var integer
     */
    protected $hasSuccess = 0;

    /**
     * [addSuccessMessage description]
     *
     * @param   string  $message         [$message description]
     * 
     * @return  array                    [return description]
     */
    public function addSuccessMessage(string $message)
    {
        $this->_successMessages[] = $message;
        return $this->_successMessages;
    }

    /**
     * [getSuccessMessages description]
     *
     * @return  array                    [return description]
     */
    public function getSuccessMessages()
    {
        return $this->_successMessages;
    }

    /**
     * [firstSuccessMessage description]
     *
     * @return  array                    [return description]
     */
    public function firstSuccessMessage()
    {
        return (is_array($this->_successMessages) && isset($this->_successMessages[0]))?$this->_successMessages[0]:"";
    }

    /**
     * [setSuccessMessages description]
     *
     * @return  array                    [return description]
     */
    public function setSuccessMessages($_successMessages)
    {
        $this->_successMessages = $_successMessages;
        return $this->_successMessages;
    }
    
    /**
     * [hasSuccesss description]
     *
     * @return  int                    [return description]
     */
    public function hasSuccess(): int
    {
        return $this->hasSuccesss;
    }

    /**
     * [setSuccess description]
     *
     * @return  int                    [return description]
     */
    public function setSuccess(): int
    {
        $this->hasSuccesss = 1;
        return $this->hasSuccesss;
    }
}
