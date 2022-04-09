<?php

namespace App\Base;
use Illuminate\Console\Command;

class BaseService
{
    /**
     * @var Command
     */
	private $command;

    protected $skip = 0;

    /**
     * [setCommand description]
     *
     * @return  [type]  [return description]
     */
    public function setCommand(Command $command)
    {
        $this->command = $command;
    }

    /**
     * [setSkip description]
     *
     * @return  [skip]  [return description]
     */
    public function setSkip($skip){
        $this->skip = ($skip!= null)?$skip:0;
    }

    /**
     * [line description]
     *
     * @return  [type]  [return description]
     */
    protected function line($param){
        if($this->command){
            $this->command->line($param);
        }else
        {
            print $param ."<br />";
        }
    }
}
