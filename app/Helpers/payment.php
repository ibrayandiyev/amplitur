<?php


if (!function_exists('sanitizeCreditCardFields')) {
    /**
     * Get remote ip address considering cloudflare
     *
     * @return  string
     */
    function sanitizeCreditCardFields($attributes)
    {
        if(!isset($attributes['formapag'])) return null;
        $payment['payment_method_id']   = $attributes['formapag'];
        $payment['installments']        = $attributes['parcelas'];
        $payment['number']              = $attributes['ct-numero'];
        $payment['holder']              = $attributes['ct-nome'];
        $expirationDate                 = null;
        if(isset($attributes['ct-mes']) && isset($attributes['ct-ano'])){
            $expirationDate = str_pad('0', 2, $attributes['ct-mes']) . '/' . $attributes['ct-ano'];
        }else{
            $expirationDate = "00/0000";
        }
        $payment['expirationDate']      = $expirationDate;
        $payment['cvv']                 = $attributes['ct-cvc'];
        return $payment;
    }
}
