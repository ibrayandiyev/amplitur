<?php

namespace App\Repositories\Traits\Bookings;

trait BookingAdditionalOperationsTrait
{

    public $_additionalStocks           = [];
    public $warningNoStockException     = 0;
    
    /**
     * [checkAdditionalStock description]
     *
     * @param   integer  $additionalId  [$additionalId description]
     * @param   integer  $currentStock  [$currentStock description]
     *
     * @return  [type]             [return description]
     */
    public function checkAdditionalStock($additionalId, $currentStock, $stock)
    {
        if(isset($this->_additionalStocks[$additionalId])){
            if($this->_additionalStocks[$additionalId] + $stock > $currentStock){
                return false;
            }
        }else{
            if($stock > $currentStock){
                return false;
            }
        }
        return true;
    }

    /**
     * [putAdditionalStock description]
     *
     * @param   integer  $additionalId  [$additionalId description]
     * @param   integer  $stock  [$stock description]
     *
     * @return  [type]             [return description]
     */
    public function putAdditionalStock($additionalId, $stock=1)
    {
        if(isset($this->_additionalStocks[$additionalId])){
            $currentStock = $this->_additionalStocks[$additionalId];
        }else{
            $currentStock = $stock;
        }
        $this->_additionalStocks[$additionalId] = $currentStock;

        return $this->_additionalStocks;
    }

    /**
     * [getAdditionalStock description]
     *
     * @param   integer  $additionalId  [$additionalId description]
     *
     * @return  [type]             [return description]
     */
    public function getAdditionalStock($additionalId)
    {
        if(isset($this->_additionalStocks[$additionalId])){
            return $this->_additionalStocks[$additionalId];
        }
        return 0;
    }

    /**
     * [setWarningNoStockException description]
     *
     * @param   integer  $value  [$value description]
     *
     * @return  [type]             [return description]
     */
    public function setWarningNoStockException($value)
    {
        $this->warningNoStockException = $value;
        return $this->warningNoStockException;
    }

        /**
     * [getWarningNoStockException description]
     *
     * @return  [type]             [return description]
     */
    public function getWarningNoStockException()
    {
        return $this->warningNoStockException;
    }
}
