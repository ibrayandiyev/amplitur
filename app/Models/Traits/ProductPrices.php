<?php

namespace App\Models\Traits;

trait ProductPrices
{

    /**
     * [getPrice description]
     *
     * @return  [type]  [return description]
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * [getPriceCurrencyConverted description]
     *
     * @return  [type]  [return description]
     */
    public function getPriceCurrencyConverted()
    {
        return moneyFloat($this->getPrice(), $this->booking->currency->code, $this->currencyOrigin->code);
    }

    /**
     * [getPriceNet description]
     *
     * @return  float   [return description]
     */
    public function getPriceNet()
    {
        return $this->price_net;
    }

    /**
     * [getPriceCurrencyConverted description]
     *
     * @return  [type]  [return description]
     */
    public function getPriceNetCurrencyConverted()
    {
        return moneyFloat($this->getPriceNet(), $this->booking->currency->code, $this->currencyOrigin->code);
    }
    
    /**
     * [getPriceCurrencyConvertedLabel description]
     *
     * @return  [type]  [return description]
     */
    public function getPriceCurrencyConvertedLabel()
    {
        return money($this->getPrice(), $this->booking->currency->code, $this->currencyOrigin->code);
    }

    /**
     * [getPriceCurrencyWOConvertedLabel description]
     *
     * @return  [type]  [return description]
     */
    public function getPriceCurrencyWOConvertedLabel()
    {
        return money($this->getPrice(), $this->booking->currency->code);
    }
}